<?php

namespace App\Models;

use App\Enums\ReservationStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Carbon\Carbon;

/**
 * Responsibility: Handles booking details, customer information, and status tracking.
 */
class Reservation extends Model
{
    use HasFactory;
    protected $fillable = [
        'reference_code',
        'table_id',
        'guest_name',
        'guest_email',
        'guest_phone',
        'party_size',
        'reservation_date',
        'reservation_time',
        'special_requests',
        'status',
    ];

 protected function casts(): array
    {
        return [
            'party_size' => 'integer',
            'reservation_date' => 'date',
            // تحويل حالة الحجز مباشرة
            // إلى الـ
            'status' => ReservationStatus::class,
        ];
    }


    /**
     *  Auto-generate reference code before saving
     */
    protected static function booted(): void
    {
        static:: creating(function (Reservation $reservation){
            if(empty($reservation->reference_code)){
                // توليد كود فريد مثل: RES-A8B9C
                $reservation->reference_code= 'RES-'.strtoupper(Str::random(5));
            }
        });
    }

    /**
     * Relationship: A reservation belongs to a specific table .
     */
    public function table(): BelongsTo
    {
        return $this->belongsTo(Table::class);
    }

/**
     * Scope: للحصول على حجوزات تاريخ معين
     */

    public function scopeForDate(Builder $query, Carbon|string $date): void {

        $query->whereDate('reservation_date',$date);
    }

    /**
     * Scope: للحصول على الحجوزات التي تحتاج إلى تأكيد
     */
    public function scopePending(Builder $query): void{
        $query->where('status',ReservationStatus::PENDING);
    }

}
