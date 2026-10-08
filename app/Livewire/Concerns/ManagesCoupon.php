<?php

declare(strict_types=1);

namespace App\Livewire\Concerns;

use App\DTOs\PricingBreakdown;
use App\Models\MenuItem;
use App\Services\CouponService;
use App\Services\PricingService;
use Livewire\Attributes\Computed;

/**
 * ═══════════════════════════════════════════════════════════════════════════
 * Trait: إدارة الكوبون داخل السلة.
 * ═══════════════════════════════════════════════════════════════════════════
 *
 * 🎯 المسؤولية:
 *    - تخزين حالة الكوبون (المطبَّق / الخطأ / المبلغ)
 *    - التحقق من الكوبون عبر CouponService
 *    - حساب التسعير الكامل عبر PricingService
 *
 * 🔑 متطلبات:
 *    - يجب استخدامه مع `ManagesCart` (للوصول إلى $cart و $totalCartAmount)
 *    - يجب استخدامه مع `RemembersCustomer` (للوصول إلى loadCustomerFromCookie)
 *
 * 🔐 القواعد:
 *    - الكوبون يتطلب عميلاً مسجّلاً (عبر remember_customer_token)
 *    - كوبون واحد فقط لكل طلب (لا تكديس)
 *    - لو تغيّرت السلة بعد التطبيق، يجب إعادة التطبيق (validation يعيد الفحص)
 *
 * 💰 التكامل مع PricingService:
 *    - `pricing` computed يُرجع PricingBreakdown كاملاً:
 *      subtotal - discount + vat = total
 *    - يُستخدم في الواجهة لعرض الملخص
 * ═══════════════════════════════════════════════════════════════════════════
 */
trait ManagesCoupon
{
    // ══════════════════════════════════════════════════════════════
    // 🎛️ الحالة (State)
    // ══════════════════════════════════════════════════════════════

    /** 📝 النص الذي يُدخله المستخدم في حقل الكوبون */
    public string $couponCode = '';

    /** 🆔 معرّف الكوبون المطبّق حالياً (null = لا كوبون) */
    public ?int $appliedCouponId = null;

    /** 🏷️ كود الكوبون المطبّق (للـ snapshot + العرض) */
    public string $appliedCouponCode = '';

    /** 💰 مبلغ الخصم المحسوب (من CouponResult) */
    public float $appliedCouponDiscount = 0.0;

    /** ⚠️ رسالة الخطأ عند فشل التطبيق (null = لا خطأ) */
    public ?string $couponError = null;

    // ══════════════════════════════════════════════════════════════
    // 🔧 الدوال العامة (Actions)
    // ══════════════════════════════════════════════════════════════

    /**
     * ✅ تطبيق الكوبون على السلة الحالية.
     *
     * التدفّق:
     *   1. التحقق من عدم فراغ الحقل
     *   2. التحقق من عدم فراغ السلة
     *   3. تحميل العميل من الكوكي (شرط أساسي)
     *   4. بناء items بصيغة CouponService
     *   5. استدعاء CouponService::validate()
     *   6. حفظ النتيجة أو عرض الخطأ
     */
    public function applyCoupon(CouponService $couponService): void
    {
        // ─── Reset الحالة السابقة ───
        $this->couponError = null;
        $this->resetAppliedCoupon();

        // ─── 1. الحقل غير فارغ ───
        $code = trim($this->couponCode);
        if ($code === '') {
            $this->couponError = __('cart.coupon.enter_code');
            return;
        }

        // ─── 2. السلة غير فارغة ───
        if ($this->isCartEmpty) {
            $this->couponError = __('cart.coupon.empty_cart');
            return;
        }

        // ─── 3. العميل مسجّل (عبر الكوكي) ───
        $customer = $this->loadCustomerFromCookie();

        if ($customer === null) {
            $this->couponError = __('cart.coupon.register_required');
            return;
        }

        // ─── 4 + 5. التحقق عبر CouponService ───
        $items  = $this->buildCouponItems();
        $result = $couponService->validate($code, $customer, $items);

        // ─── 6. معالجة النتيجة ───
        if (! $result->isValid || $result->coupon === null) {
            $this->couponError = $result->errorMessage ?? __('cart.coupon.invalid');
            return;
        }

        // ✅ حفظ النتيجة
        $this->appliedCouponId       = $result->coupon->id;
        $this->appliedCouponCode     = $result->coupon->code;
        $this->couponCode            = $result->coupon->code; // تطبيع الحقل
        $this->appliedCouponDiscount = $result->discountAmount;

        $this->dispatch('coupon-applied');
    }

    /**
     * ❌ إزالة الكوبون المطبّق.
     *
     * يُصفّر كل شيء ما عدا الرسائل الخطأ (تُصفّر أيضاً).
     */
    public function removeCoupon(): void
    {
        $this->resetCoupon();
        $this->dispatch('coupon-removed');
    }

    /**
     * 🔄 هل يوجد كوبون مطبّق حالياً؟
     */
    public function hasAppliedCoupon(): bool
    {
        return $this->appliedCouponId !== null;
    }

    // ══════════════════════════════════════════════════════════════
    // 🧮 Computed Properties
    // ══════════════════════════════════════════════════════════════

    /**
     * 💰 تفصيل التسعير الكامل.
     *
     * يعتمد على PricingService (لا تكرار للحسابات):
     *   subtotal → discount → taxable → vat → total
     *
     * ⚠️ يُستخدم في الواجهة مباشرة:
     *    $this->pricing->subtotal
     *    $this->pricing->discount
     *    $this->pricing->vat
     *    $this->pricing->total
     */
    #[Computed]
    public function pricing(): PricingBreakdown
    {
        /** @var PricingService $service */
        $service = app(PricingService::class);

        return $service->calculate(
            $this->totalCartAmount,
            $this->appliedCouponDiscount,
        );
    }

    // ══════════════════════════════════════════════════════════════
    // 🛠️ Helpers (protected)
    // ══════════════════════════════════════════════════════════════

    /**
     * 🔄 إعادة تعيين حالة الكوبون بالكامل.
     */
    protected function resetCoupon(): void
    {
        $this->resetAppliedCoupon();
        $this->couponCode  = '';
        $this->couponError = null;
    }

    /**
     * 🔄 إعادة تعيين الكوبون المطبّق فقط (لا يمسّ حقل الإدخال).
     *
     * مفيد عند فشل التحقق — نبقي النص ليعرف المستخدم ما أخطأ فيه.
     */
    protected function resetAppliedCoupon(): void
    {
        $this->appliedCouponId       = null;
        $this->appliedCouponCode     = '';
        $this->appliedCouponDiscount = 0.0;
    }

    /**
     * 🎫 تحويل عناصر السلة إلى الصيغة التي يطلبها CouponService::validate().
     *
     * الصيغة المطلوبة:
     *   array{id:int, price:float, quantity:int, category_id:?int}
     *
     * ⚠️ `category_id` غير مخزَّن في السلة — نجلبه بـ query واحد فقط.
     *
     * @return array<int, array{id:int, price:float, quantity:int, category_id:?int}>
     */
    protected function buildCouponItems(): array
    {
        if (empty($this->cart)) {
            return [];
        }

        // ✅ query واحد لجلب category_id لكل الأطباق
        $itemIds = array_keys($this->cart);

        /** @var array<int, int|null> $categoryMap */
        $categoryMap = MenuItem::query()
            ->whereIn('id', $itemIds)
            ->pluck('menu_category_id', 'id')
            ->all();

        return array_values(array_map(
            fn (array $item): array => [
                'id'          => (int) $item['id'],
                'price'       => (float) $item['price'],
                'quantity'    => (int) $item['quantity'],
                'category_id' => $categoryMap[$item['id']] ?? null,
            ],
            $this->cart,
        ));
    }
}
