<?php

declare(strict_types=1);

/**
 * ═══════════════════════════════════════════════════════════════════════════
 * 📄 المسار: app/Models/Reservation.php
 * ═══════════════════════════════════════════════════════════════════════════
 *
 * 🎯 الغرض:
 *    نموذج الحجز — يمثل جدول reservations.
 *
 * 🧩 يعتمد على:
 *    - App\Enums\ReservationStatus
 *    - App\Enums\OrderType
 *
 * ⚠️ تحذيرات مهمة:
 *    - reservation_time يجب أن يكون string (وليس datetime) لتجنّب تحويل خاطئ.
 *
 * 🕒 آخر تحديث: 2026-09-24
 * ═══════════════════════════════════════════════════════════════════════════
 */

namespace App\Models;

use App\Enums\OrderType;
use App\Enums\ReservationStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_code',
        'customer_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'party_size',
        'reservation_date',
        'reservation_time',
        'special_requests',
        'status',
    ];

    /**
     * 🎭 تحويلات الأنواع.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'party_size'       => 'integer',
            'reservation_date' => 'date',
            'reservation_time' => 'string',   // ✅ نص بسيط (H:i)
            'status'           => ReservationStatus::class,
        ];
    }

    /**
     * 🎬 أحداث النموذج — توليد reference_code تلقائياً.
     */
    protected static function booted(): void
    {
        static::creating(function (Reservation $reservation): void {
            if (empty($reservation->reference_code)) {
                $reservation->reference_code = 'RES-' . strtoupper(Str::ulid()->toBase32());
            }
        });
    }

    /**
     * 🔍 Scope: حجوزات تاريخ معين.
     */
    public function scopeForDate(Builder $query, Carbon|string $date): Builder
    {
        return $query->whereDate('reservation_date', $date);
    }

    /**
     * 🔍 Scope: الحجوزات المعلّقة فقط.
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', ReservationStatus::PENDING);
    }

    /**
     * 🔗 علاقة: الطلب المسبق المرتبط بالحجز.
     */
    public function preorder(): HasOne
    {
        return $this->hasOne(Order::class)->where('type', OrderType::PREORDER);
    }

    /**
     * 🔗 علاقة: العميل صاحب الحجز.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}