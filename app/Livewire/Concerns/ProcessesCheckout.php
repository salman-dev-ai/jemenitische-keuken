<?php

declare(strict_types=1);

namespace App\Livewire\Concerns;

use App\Enums\OrderType;
use App\Exceptions\OrderException;
use App\Services\OrderService;
use App\Services\ReservationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

/**
 * Trait: إتمام الطلب (Checkout) وبناء بيانات الحجز والطلب.
 *
 * 🎯 المسؤولية: تنفيذ تدفّق الدفع كاملاً (تحقق ← Rate Limit ← Transaction
 *    ← تنظيف الحالة ← إشعار ← تحويل إلى واتساب) وتجهيز المصفوفات
 *    التي تُمرَّر للخدمات، وبناء رسالة واتساب للمطعم.
 *
 * ⚠️ يعتمد على:
 *    - ValidatesCheckout : normalizeOrderType() و validate().
 *    - ManagesCart       : $cart, $isCartEmpty, $totalCartAmount, $this->pricing, clearCart().
 *    - ManagesCoupon     : hasAppliedCoupon(), $appliedCouponCode, resetCoupon().
 *    - RemembersCustomer : upsertCustomer(), setCustomerCookie().
 *    - خصائص المكوّن     : $order_type, $customer_*, $delivery_*, $remember_me ...
 */
trait ProcessesCheckout
{
    // ═══════════════════════════════════════════
    // 💳 إتمام الطلب (Checkout)
    // ═══════════════════════════════════════════

    /**
     * 💳 إتمام الطلب.
     *
     * 🎫 دعم الكوبون:
     *    - يُمرَّر $this->appliedCouponCode إلى OrderService
     *    - OrderService يُعيد التحقق + يحسب + يُسجّل redeem
     *    - عند النجاح: resetCoupon() لتنظيف الحالة
     *
     * 📲 واتساب: لا تُحوِّل الدالة الصفحة بنفسها، بل تُرجع رابط wa.me عند النجاح
     *    ليفتحه النموذج (Alpine) في تبويب جديد. وتُرجع null في كل حالة أخرى
     *    (سلة فارغة، تحقق فاشل، Rate Limit، خطأ، أو فشل بناء الرسالة).
     *
     * @return string|null رابط واتساب الجاهز أو null
     *
     * @throws ValidationException
     */
    public function checkout(
        ReservationService $reservationService,
        OrderService $orderService,
    ): ?string {
        $this->normalizeOrderType();
        $this->resetErrorBag();

        // ─── 1. فحص السلة الفارغة ───
        if ($this->isCartEmpty) {
            $this->dispatch(
                'notify',
                title:   __('messages.menu.empty'),
                message: '',
                type:    'warning',
            );

            return null;
        }

        // ─── 2. التحقق من المدخلات ───
        try {
            $validated = $this->validate();
        } catch (ValidationException $e) {
            $this->dispatch(
                'notify',
                title:   __('messages.order.validation_error'),
                message: $e->validator->errors()->first(),
                type:    'error',
            );

            throw $e;
        }

        // ─── 3. Rate Limiting ───
        $rateKey = 'checkout:' . request()->ip();

        if (RateLimiter::tooManyAttempts($rateKey, 5)) {
            $seconds = RateLimiter::availableIn($rateKey);
            $msg     = sprintf(__('messages.order.rate_limit_exceeded'), $seconds);

            $this->dispatch(
                'notify',
                title:   __('messages.order.error_title'),
                message: $msg,
                type:    'error',
            );

            $this->addError('general', $msg);

            return null;
        }

        RateLimiter::hit($rateKey, 60);

        // ─── 4. تطبيع رقم الهاتف ───
        $validated['customer_phone'] = $this->normalizePhone($validated['customer_phone']);

        // ─── 5. تحديد الكوبون (قبل Transaction) ───
        $couponCode = $this->hasAppliedCoupon() ? $this->appliedCouponCode : null;

        // ─── 6. التنفيذ داخل Transaction ───
        try {
            $customer = null;

            DB::transaction(function () use (
                $validated,
                $reservationService,
                $orderService,
                $couponCode,
                &$customer,
            ): void {
                // 6.1) العميل (عند تفعيل "تذكرني")
                if ($this->remember_me) {
                    $customer = $this->upsertCustomer($this->buildCustomerExtraData());
                }

                // 6.2) نوع الطلب
                $finalOrderType = OrderType::tryFrom($this->order_type);

                if ($finalOrderType === null) {
                    throw new OrderException(__('messages.errors.invalid_order_type'));
                }

                // 6.3) الحجز (فقط dine_in أو preorder)
                $reservation = null;

                if (in_array($finalOrderType, [OrderType::DINE_IN, OrderType::PREORDER], true)) {
                    $reservation = $reservationService->createReservation(
                        $this->buildReservationData($validated, $customer),
                    );
                }

                // 6.4) تجهيز عناصر الطلب
                $orderItems = collect($this->cart)
                    ->map(fn (array $item): array => [
                        'menu_item_id' => $item['id'],
                        'quantity'     => $item['quantity'],
                    ])
                    ->all();

                // 6.5) إنشاء الطلب — تمرير couponCode للخدمة
                $orderService->createOrder(
                    $this->buildOrderData($validated, $customer, $reservation, $finalOrderType),
                    $orderItems,
                    $couponCode,
                );
            });

            // ─── 7. بعد نجاح Transaction: كوكي "تذكرني" ───
            if ($this->remember_me && $customer) {
                $this->setCustomerCookie($customer);
                $this->isReturningCustomer = true;
            }

            // ─── 8. مسح Rate Limiter ───
            RateLimiter::clear($rateKey);

            // ─── 8.5. رابط واتساب (يجب بناؤه قبل تنظيف الحالة لأنه يقرأ السلة والحقول) ───
            $whatsAppUrl = $this->makeWhatsAppUrl($validated);

            // ─── 9. تنظيف الحالة ───
            $this->clearCart();
            $this->isCartModalOpen = false;
            $this->resetCustomerForm();
            $this->resetCoupon();   // 🎫 تنظيف الكوبون

            // ─── 10. إشعار النجاح ───
            $this->dispatch(
                'notify',
                title:   __('messages.notifications.order_title'),
                message: __('messages.notifications.order_message'),
                type:    'success',
            );

            // ─── 11. إرجاع رابط واتساب للنموذج ليفتحه في تبويب جديد ───
            // (بعد حفظ الطلب فعلياً، فلا يضيع الطلب إن أغلق العميل واتساب)
            return $whatsAppUrl;
        } catch (OrderException $e) {
            // ─── استثناءات العمل المعروفة ───
            report($e);

            $errMsg = $e->getMessage();

            $this->dispatch(
                'notify',
                title:   __('messages.order.error_title'),
                message: $errMsg,
                type:    'error',
            );

            $this->addError('general', $errMsg);
        } catch (\Throwable $e) {
            // ─── استثناءات غير متوقعة ───
            report($e);

            $errMsg = __('messages.order.generic_error');

            $this->dispatch(
                'notify',
                title:   __('messages.order.error_title'),
                message: $errMsg,
                type:    'error',
            );

            $this->addError('general', $errMsg);
        }

        return null;
    }

    // ═══════════════════════════════════════════
    // 📲 إرسال الطلب عبر واتساب
    // ═══════════════════════════════════════════

    /**
     * رقم واتساب المطعم بالصيغة الدولية بلا + أو أصفار (967 = مفتاح اليمن).
     * يُقرأ من config('services.whatsapp.order_number') ويُمكن ضبطه من .env،
     * وإلا يُستخدم الرقم الافتراضي 771924870.
     */
    protected function whatsAppNumber(): string
    {
  $whatsapp = preg_replace('/\D+/', '', (string) \App\Models\RestaurantSetting::current()->whatsapp ?? '');

    return $whatsapp ;    
    }

    /**
     * يبني رابط wa.me الجاهز بنص الطلب، أو null إذا فشل البناء.
     *
     * ⚠️ فشل البناء لا يجب أن يُفسد الطلب (محفوظ مسبقاً في قاعدة البيانات)،
     *    لذلك نلتقط الاستثناء ونسجّله فقط بدل رميه.
     *
     * @param array<string, mixed> $validated المدخلات بعد التحقق وتطبيع الهاتف
     */
    protected function makeWhatsAppUrl(array $validated): ?string
    {
        try {
            return 'https://wa.me/' . $this->whatsAppNumber()
                . '?text=' . rawurlencode($this->buildWhatsAppMessage($validated));
        } catch (\Throwable $e) {
            report($e);

            return null;
        }
    }

    /**
     * يبني نص رسالة الطلب (يصل لصاحب المطعم، لذلك لغته ثابتة بالعربية
     * بغض النظر عن لغة واجهة العميل).
     *
     * يشمل: بيانات العميل، الخدمة والموعد، العنوان (للتوصيل)، الأطباق،
     * الخصم، الضريبة، والإجمالي — كلها من الحالة قبل تنظيفها.
     *
     * @param array<string, mixed> $validated
     */
    protected function buildWhatsAppMessage(array $validated): string
    {
        $pricing = $this->pricing;
        $money   = static fn (float|int|string $v): string => '€' . number_format((float) $v, 2);

        $orderTypeLabels = [
            OrderType::DINE_IN->value  => 'داخل المطعم',
            OrderType::PICKUP->value   => 'استلام',
            OrderType::DELIVERY->value => 'توصيل',
            OrderType::PREORDER->value => 'طلب مسبق',
        ];

        $needsReservation = in_array($this->order_type, [
            OrderType::DINE_IN->value,
            OrderType::PREORDER->value,
        ], true);

        $lines = [
            '*طلب جديد*',
            '',
            "الاسم: {$validated['customer_name']}",
            "الهاتف: {$validated['customer_phone']}",
        ];

        if (! empty($validated['customer_email'])) {
            $lines[] = "البريد: {$validated['customer_email']}";
        }

        $lines[] = 'الخدمة: ' . ($orderTypeLabels[$this->order_type] ?? $this->order_type);

        if (! empty($validated['reservation_date'])) {
            $lines[] = "التاريخ: {$validated['reservation_date']} {$validated['reservation_time']}";
        }

        if ($needsReservation) {
            $lines[] = "عدد الأشخاص: {$this->party_size}";
        }

        if ($this->order_type === OrderType::DELIVERY->value) {
            $lines[] = "العنوان: {$validated['delivery_address']}، {$validated['delivery_city']}، {$validated['delivery_postal_code']}";
        }

        if (! empty($validated['special_requests'])) {
            $lines[] = "ملاحظات: {$validated['special_requests']}";
        }

        $lines[] = '';
        $lines[] = '*الأطباق:*';

        foreach ($this->cart as $item) {
            $lines[] = "• {$item['name']} × {$item['quantity']} = "
                . $money($item['price'] * $item['quantity']);
        }

        $lines[] = '';

        if ($pricing->discount > 0) {
            $lines[] = "الخصم ({$this->appliedCouponCode}): -" . $money($pricing->discount);
        }

        $lines[] = 'الضريبة: ' . $money($pricing->vat);
        $lines[] = '*الإجمالي: ' . $money($pricing->total) . '*';

        return implode("\n", $lines);
    }

    // ═══════════════════════════════════════════
    // 🏗️ بناء البيانات (Builder Helpers)
    // ═══════════════════════════════════════════

    /**
     * بيانات العنوان الإضافية التي تُحفظ مع العميل (للتوصيل فقط).
     *
     * @return array<string, string|null>
     */
    protected function buildCustomerExtraData(): array
    {
        if ($this->order_type !== OrderType::DELIVERY->value) {
            return [];
        }

        return [
            'address'     => $this->delivery_address ?: null,
            'city'        => $this->delivery_city ?: null,
            'postal_code' => $this->delivery_postal_code ?: null,
        ];
    }

    /**
     * بيانات الحجز: تدمج ملخص الأطباق والإجمالي والملاحظات في special_requests.
     *
     * @param  array<string, mixed>  $validated  المدخلات بعد التحقق
     * @param  object|null           $customer   العميل المحفوظ إن فُعّل "تذكرني"
     * @return array<string, mixed>
     */
    protected function buildReservationData(array $validated, $customer): array
    {
        $dishesSummary = collect($this->cart)->map(fn ($item) => sprintf(
            '%dx %s (€%.2f)',
            $item['quantity'],
            $item['name'],
            $item['price'] * $item['quantity'],
        ))->implode(', ');

        $combinedNotes = trim(sprintf(
            'Pre-order: [%s] | Total: €%.2f | Notes: %s',
            $dishesSummary,
            $this->totalCartAmount,
            $this->special_requests,
        ));

        $data = [
            'customer_name'    => $validated['customer_name'],
            'customer_phone'   => $validated['customer_phone'],
            'customer_email'   => $validated['customer_email'],
            'party_size'       => $this->party_size,
            'reservation_date' => $validated['reservation_date'],
            'reservation_time' => $validated['reservation_time'],
            'special_requests' => $combinedNotes,
        ];

        if ($customer) {
            $data['customer_id'] = $customer->id;
        }

        return $data;
    }

    /**
     * بيانات الطلب التي تُمرَّر إلى OrderService::createOrder().
     *
     * @param  array<string, mixed>  $validated
     * @param  object|null           $customer
     * @param  object|null           $reservation  الحجز المرتبط (dine_in/preorder فقط)
     * @return array<string, mixed>
     */
    protected function buildOrderData(
        array $validated,
        $customer,
        $reservation,
        OrderType $finalOrderType,
    ): array {
        $data = [
            'customer_name'        => $validated['customer_name'],
            'customer_phone'       => $validated['customer_phone'],
            'customer_email'       => $validated['customer_email'],
            'type'                 => $finalOrderType,
            'notes'                => $reservation
                ? "Linked to Reservation: {$reservation->reference_code}"
                : 'Direct order',
            'delivery_address'     => $this->delivery_address ?: null,
            'delivery_city'        => $this->delivery_city ?: null,
            'delivery_postal_code' => $this->delivery_postal_code ?: null,
        ];

        if ($customer) {
            $data['customer_id'] = $customer->id;
        }

        $data['reservation_id'] = $reservation?->id;

        return $data;
    }

    /**
     * إزالة المسافات والشرطات والأقواس من رقم الهاتف قبل الحفظ.
     */
    protected function normalizePhone(string $phone): string
    {
        return preg_replace('/[\s\-\(\)]+/', '', $phone) ?: '';
    }

    /**
     * تفريغ حقول النموذج بعد نجاح الطلب (يُرجع party_size لقيمته الافتراضية).
     */
    protected function resetCustomerForm(): void
    {
        $this->reset([
            'customer_name',
            'customer_phone',
            'customer_email',
            'special_requests',
            'party_size',
            'delivery_address',
            'delivery_city',
            'delivery_postal_code',
        ]);
    }
}