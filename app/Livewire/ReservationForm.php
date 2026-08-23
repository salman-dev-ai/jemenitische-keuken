<?php

namespace App\Livewire;

use App\Services\ReservationService;
use Exception;
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
        $this->reservation_time = '19:00';
    }

    public function submitReservation(ReservationService $reservationService): void
    {
        $this->reset(['errorMessage', 'successMessage', 'referenceCode']);

        $validated = $this->validate();

        try {
            $reservation = $reservationService->createReservation($validated);

            $this->successMessage = __('messages.reservation.success');
            $this->referenceCode = $reservation->reference_code;

            $this->reset([
                'customer_name',
                'customer_phone',
                'customer_email',
                'special_requests',
                'party_size',
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
