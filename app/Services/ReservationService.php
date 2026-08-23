<?php

namespace App\Services;

use App\Enums\ReservationStatus;
use App\Models\Reservation;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Str;

class ReservationService
{
    protected int $defaultDurationMinutes = 90;

    public function createReservation(array $data): Reservation
    {
        // 1. التحقق من الحد الأدنى للبيانات
        if (empty($data['customer_name']) || empty($data['customer_phone'])) {
            throw new Exception(__('messages.errors.missing_customer_data', default: 'يرجى إدخال الاسم ورقم الهاتف.'));
        }

        $date = $data['reservation_date'];
        $time = $data['reservation_time'];
        $partySize = (int) ($data['party_size'] ?? 1);

        if ($partySize < 1 || $partySize > 20) {
            throw new Exception(__('messages.errors.invalid_party_size', default: 'عدد الأشخاص غير صالح.'));
        }

        $reservationDate = Carbon::parse($date);
        if ($reservationDate->isPast() && ! $reservationDate->isToday()) {
            throw new Exception(__('messages.errors.past_date', default: 'لا يمكن الحجز في تاريخ ماضي.'));
        }

        // 2. ✅ التحقق من عدم وجود تعارض زمني
        if ($this->hasTimeConflict($date, $time, $data['id'] ?? null)) {
            throw new Exception(__('messages.errors.time_conflict', default: 'عذراً، هذا الوقت محجوز بالكامل. يرجى اختيار وقت آخر.'));
        }

        // 3. تجهيز البيانات
        $data['reference_code'] = $this->generateReferenceCode();
        $data['status'] = $data['status'] ?? ReservationStatus::PENDING;
        $data['party_size'] = $partySize;

        return Reservation::create($data);
    }

    public function hasTimeConflict(string $date, string $time, ?int $ignoreReservationId = null): bool
    {
        $requestedStart = Carbon::parse("{$date} {$time}");
        $requestedEnd = (clone $requestedStart)->addMinutes($this->defaultDurationMinutes);
        $bufferStart = (clone $requestedStart)->subMinutes($this->defaultDurationMinutes);

        return Reservation::query()
            ->where('reservation_date', $date)
            ->whereIn('status', [ReservationStatus::PENDING, ReservationStatus::CONFIRMED, ReservationStatus::SEATED])
            ->when($ignoreReservationId, fn ($q) => $q->where('id', '!=', $ignoreReservationId))
            ->where(function ($query) use ($bufferStart, $requestedEnd) {
                $query->whereRaw('TIME(reservation_time) < ?', [$requestedEnd->format('H:i:s')])
                    ->whereRaw('TIME(reservation_time) > ?', [$bufferStart->format('H:i:s')]);
            })
            ->limit(1) // تحسين الأداء: لا حاجة لجلب أكثر من صف واحد
            ->exists();
    }

    protected function generateReferenceCode(): string
    {
        do {
            $code = 'RES-'.now()->format('Ymd').'-'.strtoupper(Str::random(4));
        } while (Reservation::where('reference_code', $code)->exists());

        return $code;
    }
}
