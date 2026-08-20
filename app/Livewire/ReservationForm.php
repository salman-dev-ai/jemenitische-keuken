<?php



namespace App\Livewire;

use App\Models\Table;
use App\Services\ReservationService;
use Livewire\Component;
use Exception;

class ReservationForm extends Component
{
    public int $party_size = 2;
    public string $reservation_date = '';
    public string $reservation_time = '';
    public ?int $table_id = null;

    public string $customer_name = '';
    public string $customer_phone = '';
    public string $customer_email = '';
    public string $special_requests = '';

    public ?string $successMessage = null;
    public ?string $referenceCode = null;
    public ?string $errorMessage = null;

    protected function rules(): array
    {
        return [
            'party_size' => 'required|integer|min:1|max:20',
            'reservation_date' => 'required|date|after_or_equal:today',
            'reservation_time' => 'required',
            'table_id' => 'required|exists:tables,id',
            'customer_name' => 'required|string|min:3|max:255',
            'customer_phone' => 'required|string|min:8',
            'customer_email' => 'nullable|email',
            'special_requests' => 'nullable|string|max:500',
        ];
    }

    public function mount(): void
    {
        $this->reservation_date = now()->format('Y-m-d');
        $this->reservation_time = '19:00';
    }

    /**
     * تجليب الطاولات المناسبة لعدد الضيوف والمفعلة فقط
     */
    public function getAvailableTablesProperty()
    {
        return Table::query()
            ->where('is_available', true)
            ->where('capacity', '>=', $this->party_size)
            ->orderBy('capacity', 'asc')
            ->get();
    }

    public function submitReservation(ReservationService $reservationService): void
    {
        $this->reset(['errorMessage', 'successMessage', 'referenceCode']);
        $validated = $this->validate();

        try {
            $reservation = $reservationService->createReservation($validated);

            $this->successMessage = 'تم تسجيل طلب الحجز بنجاح! سنقوم بتأكيده قريباً.';
            $this->referenceCode = $reservation->reference_code;

            // إعادة ضبط الحقول
            $this->reset(['customer_name', 'customer_phone', 'customer_email', 'special_requests', 'table_id']);
        } catch (Exception $e) {
            $this->errorMessage = $e->getMessage();
        }
    }

    public function render()
    {
        return view('livewire.reservation-form');
    }


    public function availableTables(){
        return Table::query()
        ->where('is_available',true)
        ->where('capacity','>=',$this->party_size)
        ->orderBy('capacity','asc')->get();

    }
}
