<?php

namespace App\Livewire;

use App\Services\ReservationService;
use Exception;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ReservationForm extends Component
{
    #[Validate('required|integer|min:1|max:20')]
    public int $party_size = 2;

    #[Validate('required|date|after_or_equal:today')]
    public string $reservation_date = '';

    #[Validate('required')]
    public string $reservation_time = '';

    #[Validate('required|string|min:3|max:255')]
    public string $customer_name = '';

    #[Validate('required|string|min:8|max:30')]
    public string $customer_phone = '';

    #[Validate('nullable|email|max:150')]
    public string $customer_email = '';

    #[Validate('nullable|string|max:500')]
    public string $special_requests = '';

    public ?string $successMessage = null;

    public ?string $referenceCode = null;

    public ?string $errorMessage = null;

    public function mount(): void
    {
        $this->reservation_date = now()->format('Y-m-d');

        // حساب الوقت القادم المتاح (تقريب لأقرب نصف ساعة بعد ساعة من الآن)
        $time = now()->addHour();
        $minutes = $time->minute;
        $roundedMinutes = $minutes >= 30 ? 60 : 30;
        if ($roundedMinutes == 60) {
            $time->addHour()->startOfHour();
        } else {
            $time->minute = 30;
        }
        $this->reservation_time = $time->format('H:i');
    }

    public function submitReservation(ReservationService $reservationService): void
    {
        $this->reset(['errorMessage', 'successMessage', 'referenceCode']);

        // 1. حماية الـ Rate Limiting (حماية ضد البوتات 3 طلبات كل دقيقة)
        $rateLimiterKey = 'reservation-submit:'.request()->ip();
        if (RateLimiter::tooManyAttempts($rateLimiterKey, 3)) {
            $this->errorMessage = __('messages.reservation.rate_limit_exceeded') ?? 'عذراً، لقد قمت بمحاولات كثيرة. يرجى المحاولة بعد قليل.';

            return;
        }
        RateLimiter::hit($rateLimiterKey, 60);

        $validated = $this->validate();

        try {
            $reservation = $reservationService->createReservation($validated);

            $this->referenceCode = $reservation->reference_code;
            $this->successMessage = __('messages.reservation.success');

            // استخدام session()->flash() لتوحيد الواجهة وتجربة المستخدم
            session()->flash('success', $this->successMessage);

            $this->reset([
                'customer_name',
                'customer_phone',
                'customer_email',
                'special_requests',
            ]);
            $this->party_size = 2;

        } catch (Exception $e) {
            $this->errorMessage = $e->getMessage();
        }
    }

    public function render()
    {
        return view('livewire.reservation-form');
    }
}
