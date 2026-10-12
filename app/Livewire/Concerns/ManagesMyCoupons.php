<?php

declare(strict_types=1);

namespace App\Livewire\Concerns;

use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Services\CouponService;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;

/**
 * ═══════════════════════════════════════════════════════════════════════════
 * Trait: إدارة قسم "كوبوناتي" داخل SPA.
 * ═══════════════════════════════════════════════════════════════════════════
 *
 * 🎯 المسؤولية:
 *    - عرض قائمة الكوبونات المتاحة للعميل
 *    - عرض الكوبونات الترحيبية للعميل الجديد
 *    - توفير زر "نسخ" لنسخ كود الكوبون
 *    - توفير زر "تطبيق" لتحويل الكود مباشرة للسلة
 *
 * 🔑 متطلبات الاستخدام:
 *    - يجب استخدامه مع `ManagesCart` (الوصول إلى $cart و $totalCartAmount)
 *    - يجب استخدامه مع `ManagesCoupon` (الوصول إلى $couponCode و applyCoupon())
 *    - يجب استخدامه مع `RemembersCustomer` (الوصول إلى loadCustomerFromCookie())
 *
 * 💡 الفلسفة:
 *    "كوبوناتي" = قائمة تعرض الكوبونات المتاحة حالياً + ترحيبية للعميل الجديد.
 *    - عميل مسجّل → يرى كل الكوبونات المتاحة + كوبوناته الخاصة
 *    - عميل غير مسجّل → يرى معاينة للكوبونات الترحيبية مع دعوة للتسجيل
 *
 * ⚠️ ملاحظة:
 *    - لا نستخدم CouponService::claim() هنا لأن التطبيق يتم مباشرة
 *      عبر الحقل في السلة. claim() + redeem() يحدثان في OrderService عند التأكيد.
 * ═══════════════════════════════════════════════════════════════════════════
 */
trait ManagesMyCoupons
{
    // ══════════════════════════════════════════════════════════════
    // 🎛️ الحالة (State)
    // ══════════════════════════════════════════════════════════════

    /** 🪟 هل نافذة "كوبوناتي" مفتوحة؟ */
    public bool $isMyCouponsModalOpen = false;

    /** 📋 الكود الذي نُسخ مؤخراً (للـ visual feedback) */
    public ?string $copiedCouponCode = null;

    // ══════════════════════════════════════════════════════════════
    // 🎬 الأفعال (Actions)
    // ══════════════════════════════════════════════════════════════

    /**
     * 🪟 فتح نافذة "كوبوناتي".
     */
    public function openMyCoupons(): void
    {
        $this->isMyCouponsModalOpen = true;
        $this->copiedCouponCode     = null;
    }

    /**
     * 🪟 إغلاق نافذة "كوبوناتي".
     */
    public function closeMyCoupons(): void
    {
        $this->isMyCouponsModalOpen = false;
        $this->copiedCouponCode     = null;
    }

    /**
     * 📋 تعليم كود بأنه نُسخ (للـ visual feedback فقط).
     *
     * النسخ الفعلي يحدث في Alpine.js (navigator.clipboard).
     * هذه الدالة تُحدّث الحالة فقط لتغيير شكل الزر.
     */
    public function markCouponAsCopied(string $code): void
    {
        $this->copiedCouponCode = $code;

        $this->dispatch('coupon-copied', code: $code);
    }

    /**
     * ✅ تطبيق كوبون مباشرة من نافذة "كوبوناتي".
     *
     * التدفّق:
     *   1. تعيين الكود في الحقل
     *   2. استدعاء applyCoupon() (من ManagesCoupon)
     *   3. إغلاق النافذة
     *   4. فتح سلة التسوق إن لم تكن مفتوحة
     *
     * @param CouponService $couponService يُحقن تلقائياً من Livewire
     */
    public function applyCouponFromList(string $code, CouponService $couponService): void
    {
        $this->couponCode = $code;
        $this->applyCoupon($couponService);
        $this->isMyCouponsModalOpen = false;

        // ✅ افتح السلة تلقائياً إذا لم تكن مفتوحة
        if (! $this->isCartModalOpen) {
            $this->isCartModalOpen = true;
        }
    }

    // ══════════════════════════════════════════════════════════════
    // 🧮 Computed Properties
    // ══════════════════════════════════════════════════════════════

    /**
     * 🎫 قائمة الكوبونات المتاحة للعميل.
     *
     * الترتيب:
     *   1. الكوبونات الترحيبية أولاً (is_welcome = true)
     *   2. ثم الكوبونات العادية
     *   3. الأحدث إنشاءً أولاً
     *
     * @return Collection<int, Coupon>
     */
    #[Computed]
    public function myCoupons(): Collection
    {
        return Coupon::query()
            ->available() // active + notExpired + notExhausted
            ->orderByDesc('is_welcome') // الترحيبية أولاً
            ->orderByDesc('created_at') // ثم الأحدث
            ->limit(20) // حماية من الأداء
            ->get();
    }

    /**
     * 🎫 هل يوجد كوبونات لعرضها؟
     */
    #[Computed]
    public function hasMyCoupons(): bool
    {
        return $this->myCoupons->isNotEmpty();
    }
}
