<?php

declare(strict_types=1);

/**
 * ═══════════════════════════════════════════════════════════════════════════
 * 📄 المسار: app/Livewire/ReservationForm.php
 * ═══════════════════════════════════════════════════════════════════════════
 *
 * 🎯 الغرض:
 *    نموذج حجز مستقل — يتحقق، يطبّع الهاتف، يحفظ الحجز، يرسل إشعاراً.
 *
 * 🧩 يعتمد على:
 *    - App\Livewire\Concerns\RemembersCustomer
 *    - App\Services\ReservationService
 *    - App\Exceptions\ReservationException
 *
 * 🔐 الأمان:
 *    - Rate Limiting (IP فقط — قبل التحقق).
 *    - تطبيع أرقام الهاتف العربية.
 *
 * 🕒 آخر تحديث: 2026-09-24
 * ═══════════════════════════════════════════════════════════════════════════
 */

namespace App\Livewire;

use App\Exceptions\ReservationException;
use App\Livewire\Concerns\RemembersCustomer;
use App\Services\ReservationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\View\View;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Lazy]
class ReservationForm extends Component
{
    use RemembersCustomer;

    #[Validate('required|integer|min:1|max:20')]
    public int $party_size = 2;

    #[Validate('required|date|after_or_equal:today')]
    public string $reservation_date = '';

   #[Validate(['required',  'regex:/^([01]?\d|2[0-3]):[0-5]\d(:[0-5]\d)?$/',])]
    
  

public string $reservation_time = '';

    #[Validate('required|string|min:3|max:255')]
    public string $customer_name = '';

    #[Validate('required|string|min:8|max:20|regex:/^[0-9٠١٢٣٤٥٦٧٨٩+\-().\s]+$/')]
    public string $customer_phone = '';

    #[Validate('nullable|email|max:150')]
    public string $customer_email = '';

    #[Validate('nullable|string|max:500')]
    public string $special_requests = '';

    public bool $remember_me = true;

    public bool $isReturningCustomer = false;

    public ?string $successMessage = null;

    public ?string $referenceCode = null;

    public ?string $errorMessage = null;

    /**
     * 🎬 التهيئة الأولى.
     */
    public function mount(): void
    {
        // ─────────────────────────────────────────────────────────────
        // 1️⃣ التاريخ: اليوم
        // ─────────────────────────────────────────────────────────────
        $this->reservation_date = now()->format('Y-m-d');

        // ─────────────────────────────────────────────────────────────
        // 2️⃣ الوقت: أقرب نصف ساعة بعد ساعة
        // ─────────────────────────────────────────────────────────────
        $time = now()->addHour()->seconds(0);
        $roundedMinutes = (int) ceil($time->minute / 30) * 30;

        if ($roundedMinutes === 60) {
            $time->addHour()->startOfHour();
        } else {
            $time->setTime($time->hour, 0)->addMinutes($roundedMinutes);
        }

        $this->reservation_time = $time->format('H:i');

        // ─────────────────────────────────────────────────────────────
        // 3️⃣ استرجاع بيانات العميل من الكوكي
        // ─────────────────────────────────────────────────────────────
        $customer = $this->loadCustomerFromCookie();

        if ($customer) {
            $this->customer_name       = $customer->name ?? '';
            $this->customer_phone      = $customer->phone ?? '';
            $this->customer_email      = $customer->email ?? '';
            $this->isReturningCustomer = true;
        }
    }

    /**
     * 🗑️ إيقاف "تذكرني" (لا يحذف البيانات من DB).
     */
    public function forgetCustomer(): void
    {
        $this->forgetCustomerCookie();

        $this->reset([
            'customer_name',
            'customer_phone',
            'customer_email',
            'special_requests',
        ]);

        $this->isReturningCustomer = false;
        $this->remember_me         = true;
    }

    /**
     * 📅 إرسال الحجز.
     *
     * @param  ReservationService $reservationService
     * @return void
     */
    public function submitReservation(ReservationService $reservationService): void
    {
        $this->reset(['errorMessage', 'successMessage', 'referenceCode']);

        // ─────────────────────────────────────────────────────────────
        // 1️⃣ Rate Limiting (IP فقط — قبل التحقق)
        // ─────────────────────────────────────────────────────────────
        $rateKey = 'reservation:' . request()->ip();

        if (RateLimiter::tooManyAttempts($rateKey, 5)) {
            $this->errorMessage = __('messages.reservation.rate_limit_exceeded');

            return;
        }

        RateLimiter::hit($rateKey, 60);

        // ─────────────────────────────────────────────────────────────
        // 2️⃣ التحقق + تطبيع الهاتف
        // ─────────────────────────────────────────────────────────────
        try {
            $validated = $this->validate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            RateLimiter::clear($rateKey);
            throw $e;
        }

        $phone = preg_replace('/[\s\-\(\)]+/', '', $validated['customer_phone']);

        $phone = strtr($phone, [
            '٠' => '0', '١' => '1', '٢' => '2', '٣' => '3', '٤' => '4',
            '٥' => '5', '٦' => '6', '٧' => '7', '٨' => '8', '٩' => '9',
        ]);

        $validated['customer_phone'] = $phone;
        $this->customer_phone        = $phone;

        // ─────────────────────────────────────────────────────────────
        // 3️⃣ الحفظ داخل Transaction
        // ─────────────────────────────────────────────────────────────
        try {
            $customer = null;

            $reservation = DB::transaction(function () use ($validated, $reservationService, &$customer) {
                if ($this->remember_me) {
                    $customer = $this->upsertCustomer();
                    $validated['customer_id'] = $customer->id;
                }

                return $reservationService->createReservation($validated);
            });

            // ─────────────────────────────────────────────────────────
            // 4️⃣ بعد النجاح: كوكي + إشعار + تنظيف
            // ─────────────────────────────────────────────────────────
            if ($this->remember_me && $customer) {
                $this->setCustomerCookie($customer);
                $this->isReturningCustomer = true;
            }

            RateLimiter::clear($rateKey);

            $this->referenceCode  = $reservation->reference_code;
            $this->successMessage = __('messages.reservation.success');

            $this->dispatch(
                'notify',
                title:   __('messages.notifications.reservation_title'),
                message: __('messages.notifications.reservation_message'),
            );

            $this->reset([
                'customer_name',
                'customer_phone',
                'customer_email',
                'special_requests',
            ]);

            $this->party_size = 2;

        } catch (ReservationException $e) {
            report($e);
            RateLimiter::clear($rateKey);
            $this->errorMessage = $e->getMessage();

        } catch (\Throwable $e) {
            report($e);
            RateLimiter::clear($rateKey);
            $this->errorMessage = __('messages.reservation.generic_error');
        }
    }

    /**
     * ⏳ Placeholder أثناء التحميل Lazy.
     */
    public function placeholder(): View
    {
        return view('livewire.placeholders.reservation-form');
    }

    /**
     * 🎨 العرض.
     */
    public function render(): View
    {
        return view('livewire.reservation-form');
    }
}