<?php

namespace App\Models;

use App\Enums\ReservationStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_code',
        'customer_name',
        'customer_email',
        'customer_phone',
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
            'reservation_time' => 'datetime:H:i',
            'status' => ReservationStatus::class,
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Reservation $reservation): void {
            if (empty($reservation->reference_code)) {
                $reservation->reference_code = 'RES-'.strtoupper(Str::random(5));
            }
        });
    }

    public function scopeForDate(Builder $query, Carbon|string $date): Builder
    {
        return $query->whereDate('reservation_date', $date);
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', ReservationStatus::PENDING);
    }
}
