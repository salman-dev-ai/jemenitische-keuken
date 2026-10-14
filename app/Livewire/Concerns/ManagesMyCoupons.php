<?php

declare(strict_types=1);

namespace App\Livewire\Concerns;

use App\Enums\CouponUsageStatus;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Services\CouponService;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Renderless;

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
 * ⚡ الأداء والحركة:
 *    - الفتح والإغلاق تتحكم بهما Alpine في ملف النافذة (my-coupons-modal.blade.php)
 *      فتظهر اللوحة فوراً وتنزلق عند الإغلاق.
 *    - عند الإغلاق من PHP نرسل الحدث `close-my-coupons` بدل تغيير الخاصية مباشرة،
 *      حتى تعمل حركة الخروج قبل أن يُحذف العنصر.
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

    /** 🪟 هل نافذة "كوبوناتي" مفتوحة؟ (تُحمَّل البيانات فقط عند true) */
    public bool $isMyCouponsModalOpen = false;

    /** 📋 الكود الذي نُسخ مؤخراً */
    public ?string $copiedCouponCode = null;

    // ══════════════════════════════════════════════════════════════
    // 🎬 الأفعال (Actions)
    // ══════════════════════════════════════════════════════════════

    /**
     * 🪟 فتح نافذة "كوبوناتي".
     *
     * تُستدعى من Alpine بالتوازي مع ظهور اللوحة فوراً في المتصفح،
     * وتؤدي فقط إلى تحميل بيانات الكوبونات.
     */
    public function openMyCoupons(): void
    {
        $this->isMyCouponsModalOpen = true;
        $this->copiedCouponCode     = null;
    }

    /**
     * 🪟 إغلاق نافذة "كوبوناتي".
     *
     * تُستدعى من Alpine بعد انتهاء حركة الانزلاق (300ms).
     */
    public function closeMyCoupons(): void
    {
        $this->isMyCouponsModalOpen = false;
        $this->copiedCouponCode     = null;
    }

    /**
     * 📋 تعليم كود بأنه نُسخ.
     *
     * النسخ الفعلي وتغيير شكل الزر يحدثان في Alpine.js،
     * لذلك نمنع إعادة رسم المكوّن (Renderless) لتكون الاستجابة أسرع.
     */
    #[Renderless]
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
     *   3. إغلاق النافذة بحركة انزلاق (عبر حدث للمتصفح)
     *   4. فتح سلة التسوق إن لم تكن مفتوحة
     *
     * @param CouponService $couponService يُحقن تلقائياً من Livewire
     */
    public function applyCouponFromList(string $code, CouponService $couponService): void
    {
        $this->couponCode = $code;
        $this->applyCoupon($couponService);

        // ✅ كان: $this->isMyCouponsModalOpen = false;  ← يحذف العنصر فوراً بلا حركة
        // الآن Alpine يشغّل حركة الخروج ثم يستدعي closeMyCoupons() بنفسه.
        $this->dispatch('close-my-coupons');

        // ✅ افتح السلة تلقائياً إذا لم تكن مفتوحة
        // if (! $this->isCartModalOpen) {
        //     $this->isCartModalOpen = true;
        // }
    }

    // ══════════════════════════════════════════════════════════════
    // 🧮 Computed Properties
    // ══════════════════════════════════════════════════════════════

    /**
     * 🎫 قائمة الكوبونات المتاحة للعميل.
     *
     * - عميل مسجّل: كوبوناته المُطالَب بها (Claimed) والمتاحة حالياً.
     * - زائر: الكوبونات الترحيبية المتاحة.
     *
     * استعلام واحد مع eager loading (بدون استعلامات N+1).
     *
     * @return Collection<int, Coupon>
     */
    #[Computed]
    public function myCoupons(): Collection
    {
        $customer = $this->loadCustomerFromCookie();

        if ($customer !== null) {
            return CouponUsage::query()
                ->with(['coupon' => fn ($query) => $query->available()])
                ->where('customer_id', $customer->id)
                ->where('status', CouponUsageStatus::Claimed)
                ->whereHas('coupon', fn ($query) => $query->available())
                ->latest('claimed_at')
                ->get()
                ->map(fn (CouponUsage $usage) => $usage->coupon)
                ->filter()
                ->values();
        }

        return Coupon::query()
            ->welcome()
            ->available()
            ->orderByDesc('created_at')
            ->limit(20)
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