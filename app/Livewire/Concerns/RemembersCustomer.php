<?php
  
declare(strict_types=1);

namespace App\Livewire\Concerns;


use App\Models\Customer;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

/**
 * Trait لمسؤولية "تذكّر العميل" عبر كوكي + DB.
 * يوفر: تحميل، حفظ، حذف، وتوليد التوكن.
 */
trait RemembersCustomer
{
    /** 🔒 اسم الكوكي */
    protected const CUSTOMER_COOKIE_NAME = 'remember_customer_token';

    /** 🕐 مدة صلاحية الكوكي (بالأيام) */
    protected const CUSTOMER_COOKIE_DAYS = 90;

    /**
     * 🆕 قراءة التوكن من الكوكي وإرجاع العميل إن وُجد.
     * يعيد null إذا لم يوجد أو كان التوكن غير صالح.
     */
    protected function loadCustomerFromCookie(): ?Customer
    {
        $token = Cookie::get(self::CUSTOMER_COOKIE_NAME);

        if (blank($token)) {
            return null;
        }

        return Customer::where('remember_token', $token)->first();
    }

    /**
     * 🆕 ضبط كوكي العميل العائد بعد نجاح العملية.
     */
    protected function setCustomerCookie(Customer $customer): void
    {
        Cookie::queue(Cookie::make(
            name:     self::CUSTOMER_COOKIE_NAME,
            value:    $customer->remember_token,
            minutes:  self::CUSTOMER_COOKIE_DAYS * 1440,
            path:     '/',
            domain:   null,
            secure:   config('reservations.cookie.secure', true),
            httpOnly: true,
            raw:      false,
            sameSite: config('reservations.cookie.same_site', 'lax'),
        ));
    }

    /**
     * 🆕 حذف الكوكي + تدمير التوكن في DB.
     */
 

      /**
     * 🗑️ إيقاف "تذكرني" (دون حذف العميل من قاعدة البيانات).
     *
     * - يُصفّر remember_token في DB.
     * - يحذف الكوكي من المتصفح (بانتهاء صلاحيته).
     * - يحتفظ بسجل العميل وعلاقاته للاستخدام المستقبلي.
     *
     * 📌 الحيلة: نستخدم Cookie::make مع minutes سالبة (منتهية)
     *    بدل Cookie::forget — لأن forget لا تقبل secure/sameSite
     *    وبالتالي قد لا تحذف الكوكي بشكل صحيح في بعض المتصفحات.
     *
     * 📌 المصدر: https://laravel.com/docs/12.x/requests#cookies
     *
     * @return void
     */
    protected function forgetCustomerCookie(): void
    {
        $token = Cookie::get(self::CUSTOMER_COOKIE_NAME);

        if (filled($token)) {
            Customer::where('remember_token', $token)
                ->update(['remember_token' => null]);
        }

        // ✅ إنشاء كوكي منتهي بنفس خصائص setCustomerCookie
        //    minutes سالبة → المتصفح يحذفه فوراً
        Cookie::queue(Cookie::make(
            name:     self::CUSTOMER_COOKIE_NAME,
            value:    '',
            minutes:  -2628000,             // 5 سنوات في الماضي
            path:     '/',
            domain:   null,
            secure:   config('reservations.cookie.secure', true),
            httpOnly: true,
            raw:      false,
            sameSite: config('reservations.cookie.same_site', 'lax'),
        ));
    }

// ... (بقية الـ Trait)

    /**
     * 🆕 توليد توكن آمن للعميل.
     */
    protected function generateRememberToken(): string
    {
        return Str::random(64);
    }

    /**
     * 🆕 إنشاء أو تحديث سجل العميل.
     * يجب أن يُستدعى داخل DB::transaction.
     *
     * @param array $additionalData حقول إضافية (عنوان، مدينة، ... إلخ)
     */
        /**
     * 🆕 إنشاء أو تحديث سجل العميل.
     * يجب أن يُستدعى داخل DB::transaction.
     *
     * 🎯 المعرّف الوحيد: رقم الهاتف (مُطبَّع).
     *    - لا نستخدم email كمعرّف (قد يكون مشتركاً بين أفراد الأسرة).
     *    - إذا كان الهاتف جديداً → سجل جديد (حتى لو تطابق الإيميل).
     *
     * @param array $additionalData حقول إضافية (عنوان، مدينة، ... إلخ)
     */
    protected function upsertCustomer(array $additionalData = []): Customer
    {
        // 🎯 تطبيع الهاتف أولاً — لضمان توحيد الصيغة
        $normalizedPhone = $this->normalizePhone($this->customer_phone);

        $customer = Customer::query()
            ->where('phone', $normalizedPhone)   // ✅ phone فقط
            ->lockForUpdate()
            ->first();

        if (! $customer) {
            $customer = new Customer();
        }

        $customer->fill(array_merge([
            'name'          => $this->customer_name,
            'phone'         => $normalizedPhone,   // ✅ مُطبَّع
            'email'         => $this->customer_email ?: null,
            'last_order_at' => now(),
        ], $additionalData));

        if (blank($customer->remember_token)) {
            $customer->remember_token = $this->generateRememberToken();
        }

        $customer->save();

        return $customer;
    }


        /**
     * 🆕 تطبيع رقم الهاتف — للتوحيد قبل البحث/الحفظ.
     *
     * - يحوّل الأرقام العربية-الهندية (٠-٩) إلى ASCII (0-9)
     * - يحذف المسافات والرموز: `-` `(` `)` `.`
     * - يُبقي `+` في بدايتها فقط
     *
     * @param  string $phone
     * @return string
     */
    protected function normalizePhone(string $phone): string
    {
        // 1️⃣ تحويل الأرقام العربية-الهندية إلى ASCII
        $arabic = ['٠','١','٢','٣','٤','٥','٦','٧','٨','٩'];
        $ascii  = ['0','1','2','3','4','5','6','7','8','9'];
        $phone  = str_replace($arabic, $ascii, $phone);

        // 2️⃣ حذف كل ما ليس رقماً أو + (نُبقي + فقط)
        return preg_replace('/[^\d+]/', '', $phone) ?: '';
    }
    
}