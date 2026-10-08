<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Enums\OrderType;
use App\Exceptions\OrderException;
use App\Livewire\Concerns\ManagesCart;
use App\Livewire\Concerns\ManagesCoupon;
use App\Livewire\Concerns\RemembersCustomer;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Services\OrderService;
use App\Services\ReservationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class MenuWithCart extends Component
{
    use ManagesCart;
    use ManagesCoupon;
    use RemembersCustomer;

    // ═══════════════════════════════════════════
    // 🎛️ حالة المكوّن (State)
    // ═══════════════════════════════════════════

    public string $selectedCategorySlug = 'all';
    public bool $isCartModalOpen = false;

    // ═══════════════════════════════════════════
    // 👤 حقول العميل
    // ═══════════════════════════════════════════

    public string $customer_name = '';
    public string $customer_phone = '';
    public string $customer_email = '';
    public bool $remember_me = true;
    public bool $isReturningCustomer = false;

    // ═══════════════════════════════════════════
    // 🚚 حقول التوصيل
    // ═══════════════════════════════════════════

    public string $delivery_address = '';
    public string $delivery_city = '';
    public string $delivery_postal_code = '';

    // ═══════════════════════════════════════════
    // 📅 حقول الحجز
    // ═══════════════════════════════════════════

    public string $reservation_date = '';
    public string $reservation_time = '19:30';
    public int $party_size = 2;
    public string $order_type = 'dine_in';
    public string $special_requests = '';

    // ═══════════════════════════════════════════
    // 🔄 دورة الحياة (Lifecycle)
    // ═══════════════════════════════════════════

    public function mount(): void
    {
        $this->reservation_date = now()->format('Y-m-d');
        $this->initializeCart();
        $this->hydrateCustomerFields();
    }

    /**
     * 🆕 تعبئة حقول العميل من الكوكي إن وُجد.
     */
    protected function hydrateCustomerFields(): void
    {
        $customer = $this->loadCustomerFromCookie();

        if (! $customer) {
            return;
        }

        $this->customer_name        = $customer->name ?? '';
        $this->customer_phone       = $customer->phone ?? '';
        $this->customer_email       = $customer->email ?? '';
        $this->delivery_address     = $customer->address ?? '';
        $this->delivery_city        = $customer->city ?? '';
        $this->delivery_postal_code = $customer->postal_code ?? '';
        $this->isReturningCustomer  = true;
    }

    /**
     * 🔄 Livewire hook — يُستدعى عند أي تعديل على $cart.
     *
     * 🎯 الهدف: إذا تغيّرت السلة (إضافة/حذف عنصر)،
     *    فإن الكوبون المطبّق قد لا يبقى صالحاً (min_order_total،
     *    منتجات محددة، إلخ). لذا نُزيله لتجنب عرض خصم قديم.
     *
     * ⚠️ ملاحظة: عند checkout، يتم التحقق من الكوبون مرة أخرى
     *    داخل OrderService (لضمان عدم استنفاده بين التطبيق والتأكيد).
     */
    public function updatedCart(): void
    {
        if ($this->hasAppliedCoupon()) {
            $this->resetAppliedCoupon();
            $this->couponError = __('cart.coupon.cart_changed_reapply');
        }
    }

    // ═══════════════════════════════════════════
    // 🆕 زر "لست أنا؟"
    // ═══════════════════════════════════════════

    public function forgetCustomer(): void
    {
        $this->forgetCustomerCookie();

        $this->reset([
            'customer_name',
            'customer_phone',
            'customer_email',
            'delivery_address',
            'delivery_city',
            'delivery_postal_code',
            'special_requests',
        ]);

        // 🎫 إزالة الكوبون أيضاً — لأن الكوبون يتطلب عميلاً
        $this->resetCoupon();

        $this->isReturningCustomer = false;
        $this->remember_me         = true;

        $this->dispatch(
            'notify',
            title:   __('messages.remember.forgotten_title'),
            message: __('messages.remember.forgotten_message'),
        );
    }

    // ═══════════════════════════════════════════
    // 🧾 قواعد التحقق (ديناميكية حسب نوع الطلب)
    // ═══════════════════════════════════════════

    /**
     * @return array<string, mixed>
     */
    protected function rules(): array
    {
        $isDelivery       = $this->order_type === OrderType::DELIVERY->value;
        $needsReservation = in_array($this->order_type, [
            OrderType::DINE_IN->value,
            OrderType::PREORDER->value,
        ], true);

        $minPartySize = (int) config('reservations.party_size.min', 1);
        $maxPartySize = (int) config('reservations.party_size.max', 20);

        return [
            // ─── بيانات العميل ───
            'customer_name'  => ['required', 'string', 'min:3', 'max:100'],
            'customer_phone' => [
                'required',
                'string',
                'min:7',
                'max:30',
                'regex:/^[0-9٠١٢٣٤٥٦٧٨٩+\-().\s]+$/',
            ],
            'customer_email' => ['nullable', 'email', 'max:150'],

            // ─── التوصيل ───
            'delivery_address' => [
                $isDelivery ? 'required' : 'nullable',
                'string',
                'max:255',
            ],
            'delivery_city' => [
                $isDelivery ? 'required' : 'nullable',
                'string',
                'max:255',
            ],
            'delivery_postal_code' => [
                $isDelivery ? 'required' : 'nullable',
                'string',
                'max:30',
                'regex:/^\d{4}\s?[A-Za-z]{2}$/',
            ],

            // ─── الحجز ───
            'reservation_date' => [
                $needsReservation ? 'required' : 'nullable',
                'date',
                'after_or_equal:today',
            ],
            'reservation_time' => [
                $needsReservation ? 'required' : 'nullable',
                'string',
                'regex:/^([01]?\d|2[0-3]):[0-5]\d(:[0-5]\d)?$/',
            ],
            'party_size' => [
                $needsReservation ? 'required' : 'nullable',
                'integer',
                'min:' . $minPartySize,
                'max:' . $maxPartySize,
            ],

            'special_requests' => ['nullable', 'string', 'max:500'],

            // ─── نوع الطلب ───
            'order_type' => [
                'required',
                'string',
                Rule::in([
                    OrderType::DINE_IN->value,
                    OrderType::PICKUP->value,
                    OrderType::PREORDER->value,
                    OrderType::DELIVERY->value,
                ]),
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function messages(): array
    {
        return [
            'delivery_address.required'     => __('messages.order.address_required'),
            'delivery_city.required'        => __('messages.order.city_required'),
            'delivery_postal_code.required' => __('messages.order.postal_required'),
            'customer_phone.regex'          => __('messages.order.phone_format'),
        ];
    }

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
     * @throws \Illuminate\Validation\ValidationException
     */
    public function checkout(
        ReservationService $reservationService,
        OrderService $orderService,
    ): void {
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

            return;
        }

        // ─── 2. التحقق من المدخلات ───
        try {
            $validated = $this->validate();
        } catch (\Illuminate\Validation\ValidationException $e) {
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

            return;
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
    }

    // ═══════════════════════════════════════════
    // 🏗️ بناء البيانات (Builder Helpers)
    // ═══════════════════════════════════════════

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

    protected function normalizePhone(string $phone): string
    {
        return preg_replace('/[\s\-\(\)]+/', '', $phone) ?: '';
    }

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

    // ═══════════════════════════════════════════
    // 🗂️ القائمة والتصنيفات
    // ═══════════════════════════════════════════

    public function selectCategory(string $slug): void
    {
        $this->selectedCategorySlug = $slug;
        unset($this->filteredItems);
    }

    #[Computed(persist: true)]
    public function categories()
    {
        return MenuCategory::active()
            ->withCount(['menuItems' => fn ($q) => $q->available()])
            ->orderBy('sort_order')
            ->get();
    }

    #[Computed]
    public function filteredItems()
    {
        return MenuItem::available()
            ->with('category')
            ->when(
                $this->selectedCategorySlug !== 'all',
                fn ($q) => $q->whereHas(
                    'category',
                    fn ($c) => $c->where('slug', $this->selectedCategorySlug),
                ),
            )
            ->orderBy('sort_order')
            ->get();
    }

    // ═══════════════════════════════════════════
    // 🔄 Order Type Normalization
    // ═══════════════════════════════════════════

    protected function normalizeOrderType(): void
    {
        $legacyMap = [
            'dineIn'   => OrderType::DINE_IN->value,
            'takeaway' => OrderType::PICKUP->value,
        ];

        if (isset($legacyMap[$this->order_type])) {
            $this->order_type = $legacyMap[$this->order_type];
        }
    }

    public function updatedOrderType(mixed $value): void
    {
        $this->normalizeOrderType();
    }

    // ═══════════════════════════════════════════
    // 🖼️ العرض
    // ═══════════════════════════════════════════

    public function placeholder(): View
    {
        return view('livewire.placeholders.menu-with-cart');
    }

    public function render(): View
    {
        return view('livewire.menu-with-cart');
    }
}
