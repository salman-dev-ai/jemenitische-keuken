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
     * - يحذف الكوكي من المتصفح.
     * - يحتفظ بسجل العميل وعلاقاته للاستخدام المستقبلي.
     *
     * 📌 المصدر: https://laravel.com/docs/12.x/responses#deleting-cookies
     *
     * @return void
     */
    protected function forgetCustomerCookie(): void
    {
        $token = Cookie::get(self::CUSTOMER_COOKIE_NAME);

        if (filled($token)) {
            Customer::where('remember_token', $token)
                ->update(['remember_token' => null]);  // ✅ لا حذف
        }

        Cookie::queue(Cookie::forget(
            name: self::CUSTOMER_COOKIE_NAME,
            path: '/',
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
    protected function upsertCustomer(array $additionalData = []): Customer
    {
        $customer = Customer::query()
            ->where(function ($q) {
                $q->where('phone', $this->customer_phone);

                if (filled($this->customer_email)) {
                    $q->orWhere('email', $this->customer_email);
                }
            })
            ->lockForUpdate()
            ->first();

        if (! $customer) {
            $customer = new Customer();
        }

        $customer->fill(array_merge([
            'name'          => $this->customer_name,
            'phone'         => $this->customer_phone ?: null,
            'email'         => $this->customer_email ?: null,
            'last_order_at' => now(),
        ], $additionalData));

        if (blank($customer->remember_token)) {
            $customer->remember_token = $this->generateRememberToken();
        }

        $customer->save();

        return $customer;
    }
}