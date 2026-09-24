<?php
// D:\YearTherPartOne\learn-php\projects\jemenitische-keuken-chat-v-valide\jemenitische-keuken-chat\app\Services\ReservationService.php
declare(strict_types=1);

namespace App\Services;

use App\Enums\ReservationStatus;
use App\Exceptions\ReservationException;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ReservationService
{
    protected int $defaultDurationMinutes;

    public function __construct()
    {
        $this->defaultDurationMinutes = (int) config(
            'reservations.default_duration_minutes',
            90
        );
    }

    /**
     * إنشاء حجز جديد مع التحقق الكامل من التعارض.
     *
     * @throws ReservationException
     */
    public function createReservation(array $data): Reservation
    {
        // 1. التحقق من المدخلات (خارج Transaction لتوفير الموارد)
        $this->validateInput($data);

        // 2. العملية داخل Transaction
        return DB::transaction(function () use ($data) {
            $date      = $data['reservation_date'];
            $time      = $data['reservation_time'];
            $partySize = (int) $data['party_size'];

       

            // 2.2 تجهيز البيانات
            $status = $this->resolveStatus($data['status'] ?? null);

            $reservationData = Arr::only($data, [
                'customer_id',
                'customer_name',
                'customer_email',
                'customer_phone',
                'special_requests',
            ]);

            $reservationData['reference_code']   = $this->generateReferenceCode();
            $reservationData['party_size']       = $partySize;
            $reservationData['reservation_date'] = $date;
            $reservationData['reservation_time'] = $time;
            $reservationData['status']           = $status;

            // 2.3 الإنشاء
            $reservation = Reservation::create($reservationData);

            // 2.4 تسجيل للتدقيق
            Log::info('Reservation created', [
                'id'             => $reservation->id,
                'reference_code' => $reservation->reference_code,
                'customer_id'    => $reservation->customer_id,
                'date'           => $date,
                'time'           => $time,
            ]);

            return $reservation;
        });
    }

    /**
     * فحص وجود حجز متعارض في نفس الفترة.
     */
    public function hasConflictingReservation(
        string $date,
        string $time,
        ?int $ignoreReservationId = null
    ): bool {
        $start = Carbon::parse("{$date} {$time}");
        $end   = (clone $start)->addMinutes($this->defaultDurationMinutes);

        return Reservation::query()
            ->where('reservation_date', $date)
            ->whereIn('status', [
                ReservationStatus::PENDING,
                ReservationStatus::CONFIRMED,
                ReservationStatus::SEATED,
            ])
            ->when($ignoreReservationId, fn ($q) => $q->where('id', '!=', $ignoreReservationId))
            ->where(function ($query) use ($start, $end) {
                // التداخل: existing.start < new.end AND existing.end > new.start
                $query->where('reservation_time', '<', $end->format('H:i:s'))
                      ->whereRaw(
                          'ADDTIME(reservation_time, SEC_TO_TIME(? * 60)) > ?',
                          [$this->defaultDurationMinutes, $start->format('H:i:s')]
                      );
            })
            ->exists();
    }

    /**
     * التحقق من صحة المدخلات.
     *
     * @throws ReservationException
     */
    protected function validateInput(array $data): void
    {
        // بيانات العميل
        if (empty($data['customer_name']) || empty($data['customer_phone'])) {
            throw ReservationException::missingCustomerData();
        }

        // الحقول الأساسية
        if (empty($data['reservation_date']) || empty($data['reservation_time'])) {
            throw ReservationException::missingRequiredFields();
        }

        // صيغة التاريخ
        if (! preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['reservation_date'])) {
            throw ReservationException::invalidDateFormat();
        }

        // صيغة الوقت
        if (! preg_match('/^\d{2}:\d{2}(:\d{2})?$/', $data['reservation_time'])) {
            throw ReservationException::invalidTimeFormat();
        }

        // عدد الأشخاص
        $partySize = (int) ($data['party_size'] ?? 1);
        if ($partySize < 1 || $partySize > 20) {
            throw ReservationException::invalidPartySize();
        }

        // التاريخ في الماضي
        $reservationDate = Carbon::parse($data['reservation_date']);
        if ($reservationDate->isPast() && ! $reservationDate->isToday()) {
            throw ReservationException::pastDate();
        }
    }

    /**
     * تحويل القيمة المرسلة لـ status إلى Enum صالح.
     *
     * @throws ReservationException
     */
    protected function resolveStatus(mixed $status): ReservationStatus
    {
        if ($status instanceof ReservationStatus) {
            return $status;
        }

        if (is_string($status) && $resolved = ReservationStatus::tryFrom($status)) {
            return $resolved;
        }

        return ReservationStatus::PENDING;
    }

    /**
     * توليد كود مرجعي فريد.
     */
    protected function generateReferenceCode(): string
    {
        return 'RES-'.strtoupper(Str::ulid()->toBase32());
    }
}