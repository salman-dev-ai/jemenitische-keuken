<?php

namespace App\Livewire;

use App\Livewire\Concerns\RemembersCustomer;
use App\Services\ReservationService;
use Exception;
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

    #[Validate('required|date_format:H:i')]
    public string $reservation_time = '';

    #[Validate('required|string|min:3|max:255')]
    public string $customer_name = '';

    #[Validate('required|string|min:8|max:20|regex:/^[0-9+\-\s()]+$/')]
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

    public function mount(): void
    {
        $this->reservation_date = now()->format('Y-m-d');

        // حساب الوقت القادم المتاح (تقريب لأقرب نصف ساعة بعد ساعة)
        $time = now()->addHour()->seconds(0);
        $roundedMinutes = (int) ceil($time->minute / 30) * 30;

        if ($roundedMinutes === 60) {
            $time->addHour()->startOfHour();
        } else {
            $time->setTime($time->hour, 0)->addMinutes($roundedMinutes);
        }

        $this->reservation_time = $time->format('H:i');

        // 🆕 تحميل بيانات العميل إن وُجد
        $customer = $this->loadCustomerFromCookie();

        if ($customer) {
            $this->customer_name       = $customer->name ?? '';
            $this->customer_phone      = $customer->phone ?? '';
            $this->customer_email      = $customer->email ?? '';
            $this->isReturningCustomer = true;
        }
    }

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

    public function submitReservation(ReservationService $reservationService): void
    {
        $this->reset(['errorMessage', 'successMessage', 'referenceCode']);

        $rateKey = 'reservation:'.request()->ip().':'.sha1($this->customer_phone);

        if (RateLimiter::tooManyAttempts($rateKey, 5)) {
            $this->errorMessage = __('messages.reservation.rate_limit_exceeded');

            return;
        }

        RateLimiter::hit($rateKey, 60);

        $validated = $this->validate();
        $validated['customer_phone'] = preg_replace('/[\s\-\(\)]+/', '', $validated['customer_phone']);

        try {
            $customer = null;

            $reservation = DB::transaction(function () use ($validated, $reservationService, &$customer) {
                if ($this->remember_me) {
                    $customer = $this->upsertCustomer();
                    $validated['customer_id'] = $customer->id;
                }

                return $reservationService->createReservation($validated);
            });

            if ($this->remember_me && $customer) {
                $this->setCustomerCookie($customer);
                $this->isReturningCustomer = true;
            }

            RateLimiter::clear($rateKey);

            $this->referenceCode  = $reservation->reference_code;
            $this->successMessage = __('messages.reservation.success');

            $this->dispatch('notify',
                title:   __('messages.notifications.reservation_title'),
                message: __('messages.notifications.reservation_message'),
            );

            $this->reset([
                'customer_name',
                'customer_phone',
                'customer_email',
                'special_requests',
            ]);

            $this->reset('party_size');

        } catch (Exception $e) {
            report($e);
            $this->errorMessage = __('messages.reservation.generic_error');
        }
    }

    public function placeholder(): View
    {
        return view('livewire.placeholders.reservation-form');
    }

    public function render(): View
    {
        return view('livewire.reservation-form');
    }
}