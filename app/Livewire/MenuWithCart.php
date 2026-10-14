<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Livewire\Concerns\ManagesCart;
use App\Livewire\Concerns\ManagesCoupon;
use App\Livewire\Concerns\ManagesMenu;
use App\Livewire\Concerns\ManagesMyCoupons;
use App\Livewire\Concerns\ProcessesCheckout;
use App\Livewire\Concerns\RemembersCustomer;
use App\Livewire\Concerns\ValidatesCheckout;
use App\Services\CouponService;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

/**
 * المكوّن الرئيسي لصفحة القائمة + السلة + الحجز.
 *
 * 🎯 مسؤولية هذا الملف فقط:
 *    - تعريف الحالة (State) التي تقرؤها ملفات Blade.
 *    - دورة الحياة (mount) ومستمعو الأحداث (Events).
 *    - تعبئة/نسيان بيانات العميل من الكوكي.
 *    - العرض (render / placeholder).
 *
 * 🧩 باقي المنطق موزّع على Traits داخل app/Livewire/Concerns:
 *    - ManagesCart        : عمليات السلة (إضافة/حذف/كمية/تسعير).
 *    - ManagesCoupon      : تطبيق/إزالة الكوبون داخل السلة.
 *    - ManagesMyCoupons   : نافذة "كوبوناتي".
 *    - RemembersCustomer  : كوكي "تذكرني" وحفظ العميل.
 *    - ManagesMenu        : عرض الأطباق والتصنيفات والفلترة.
 *    - ValidatesCheckout  : قواعد التحقق ورسائلها وتطبيع نوع الطلب.
 *    - ProcessesCheckout  : إتمام الطلب وبناء بيانات الحجز/الطلب.
 *
 * ⚠️ الـ Traits تعتمد على خصائص هذا المكوّن (مثل $order_type و$cart)،
 *    لذلك أي خاصية جديدة تُضاف هنا لا داخل الـ Trait.
 *
 * ℹ️ دالة placeholder() لا تُستخدم إلا إذا فُعّل الخيار #[Lazy]
 *    (أضف `use Livewire\Attributes\Lazy;` ثم الوسم فوق الكلاس).
 */
class MenuWithCart extends Component
{
    use ManagesCart;
    use ManagesCoupon;
    use ManagesMenu;
    use ManagesMyCoupons;
    use ValidatesCheckout;

    // ⚠️ حل تعارض: normalizePhone() معرّفة في الـ Trait كليهما.
    //    نعتمد نسخة ProcessesCheckout (نفس سلوك الملف الأصلي حين كانت
    //    دالة المكوّن تتغلب على دالة RemembersCustomer).
    use ProcessesCheckout, RemembersCustomer {
        ProcessesCheckout::normalizePhone insteadof RemembersCustomer;
    }

    // ═══════════════════════════════════════════
    // 🎛️ حالة المكوّن (State)
    // ═══════════════════════════════════════════

    /** slug التصنيف المحدد حالياً ('all' = كل الأطباق). يستخدمه ManagesMenu. */
    public string $selectedCategorySlug = 'all';

    /** هل نافذة السلة مفتوحة؟ (تُفتح تلقائياً عند تطبيق كوبون من العروض). */
    public bool $isCartModalOpen = false;

    // ═══════════════════════════════════════════
    // 👤 حقول العميل
    // ═══════════════════════════════════════════

    public string $customer_name = '';
    public string $customer_phone = '';
    public string $customer_email = '';

    /** تفعيل "تذكرني" لحفظ بيانات العميل في الكوكي بعد نجاح الطلب. */
    public bool $remember_me = true;

    /** هل عُبّئت الحقول من كوكي عميل سابق؟ (يُظهر شريط "مرحباً بعودتك"). */
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

    /**
     * تهيئة المكوّن عند أول تحميل: تاريخ اليوم، السلة، وبيانات العميل المحفوظة.
     */
    public function mount(): void
    {
        $this->reservation_date = now()->format('Y-m-d');
        $this->initializeCart();
        $this->hydrateCustomerFields();
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
    // 📡 مستمعو الأحداث (Event Listeners)
    // ═══════════════════════════════════════════

    /**
     * مزامنة حقول العميل عند إطلاق حدث "customer-remembered"
     * (مثلاً بعد حفظ العميل من نافذة الكوبون).
     */
    #[On('customer-remembered')]
    public function syncCustomerFromCookie(): void
    {
        $this->hydrateCustomerFields();

        if ($this->isReturningCustomer) {
            $this->remember_me = true;
        }
    }

    /**
     * 🎯 يُستدعى عند إطلاق coupon-modal حدث "apply-coupon-to-cart".
     *
     * التدفّق:
     *   1. يُعيّن الكود في الحقل
     *   2. يستدعي applyCoupon() من ManagesCoupon (يستخدم CouponService)
     *   3. يفتح نافذة السلة تلقائياً
     *
     * ⚠️ ملاحظة: الكوكي (remember_customer_token) تم ضبطه بالفعل
     *    من coupon-modal، لذا loadCustomerFromCookie() سيعمل.
     */
    #[On('apply-coupon-to-cart')]
    public function applyCouponFromOffer(string $code, CouponService $couponService): void
    {
        $this->couponCode = $code;
        $this->applyCoupon($couponService);

        if (! $this->isCartModalOpen) {
            $this->isCartModalOpen = true;
        }
    }

    // ═══════════════════════════════════════════
    // 🆕 بيانات العميل المحفوظة (تذكرني)
    // ═══════════════════════════════════════════

    /**
     * 🆕 تعبئة حقول العميل من الكوكي إن وُجد.
     */
    protected function hydrateCustomerFields(): void
    {
        $customer = $this->loadCustomerFromCookie();

        if (! $customer) {
            $this->isReturningCustomer = false;

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
     * زر "لست أنا؟": يحذف الكوكي ويفرّغ بيانات العميل والكوبون ثم يُرسل إشعاراً.
     */
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
    // 🖼️ العرض
    // ═══════════════════════════════════════════

    /**
     * واجهة الانتظار المؤقتة (تُعرض فقط مع التحميل الكسول #[Lazy]).
     */
    public function placeholder(): View
    {
        return view('livewire.placeholders.menu-with-cart');
    }

    /**
     * عرض قالب المكوّن.
     */
    public function render(): View
    {
        return view('livewire.menu-with-cart');
    }
}