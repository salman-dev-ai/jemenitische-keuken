<?php
namespace App\Services;

use App\Enums\ReservationStatus;
use App\Models\Reservation;
use App\Models\Table;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Exception;

class ReservationService
{
    /**
     * مدة الحجز الافتراضية بالدقائق لتحديد التعارضات الزمانية
     */
    protected int $defaultDurationMinutes = 120;

    /**
     * إنشاء حجز جديد مع التحقق الكامل من القيود
     */

    public function createReservation(array $data): Reservation
    {
        $table = Table::findOrFail($data['table_id']);

        // 1. التحقق من سعة الطاولة
        if ($table->capacity < $data['party_size']) {
            throw new Exception("الطاولة رقم ({$table->table_number}) لا تتسع لـ {$data['party_size']} أشخاص. الحد الأقصى لها هو {$table->capacity}.");
        }
        // 2. التحقق من تفعيل الطاولة
        if (! $table->is_active) {
            throw new Exception("الطاولة المختارة غير متاحة للحجز حالياً.");
        }

        // 3. التحقق من عدم وجود تعارض زمني
        if ($this->hasTimeConflict($data['table_id'], $data['reservation_date'], $data['reservation_time'])) {
            throw new Exception("الطاولة محجوزة بالفعل في هذا الوقت أو في نطاق ساعتين منه. يرجى اختيار وقت آخر أو طاولة مختلفة.");
        }

        // 4. إعداد البيانات وتوليد رقم المرجع
        $data['reference_code'] = $this->generateReferenceCode();
        $data['status'] = $data['status'] ?? ReservationStatus::PENDING;

        return Reservation::create($data);
    }

    /**
     * التحقق من وجود تعارض في وقت الحجز لنفس الطاولة
     */
    public function hasTimeConflict(int $tableId, string $date, string $time, ?int $ignoreReservationId = null): bool
    {
        $requestedStart = Carbon::parse("{$date} {$time}");
        $requestedEnd = (clone $requestedStart)->addMinutes($this->defaultDurationMinutes);

        return Reservation::query()
            ->where('table_id', $tableId)
            ->where('reservation_date', $date)
            ->whereIn('status', [ReservationStatus::PENDING, ReservationStatus::CONFIRMED])
            ->when($ignoreReservationId, fn($query) => $query->where('id', '!=', $ignoreReservationId))
            ->get()
            ->filter(function (Reservation $reservation) use ($requestedStart, $requestedEnd) {
                $existingStart = Carbon::parse("{$reservation->reservation_date} {$reservation->reservation_time}");
                $existingEnd = (clone $existingStart)->addMinutes($this->defaultDurationMinutes);

                // فحص التداخل بين النطاقين الزمنيّين
                return $requestedStart->lt($existingEnd) && $requestedEnd->gt($existingStart);
            })
            ->isNotEmpty();
    }

    /**
     * توليد كود مرجعي فريد للحجز
     */
    protected function generateReferenceCode(): string
    {
        do {
            $code = 'RES-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4));
        } while (Reservation::where('reference_code', $code)->exists());

        return $code;
    }
}
