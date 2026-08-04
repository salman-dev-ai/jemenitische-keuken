<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Responsibility: Manages restaurant tables,
 *  their capacities, and relates to their reservations.
 */
class Table extends Model
{
    protected $fillable = [
        'table_number',
        'capacity',
        'location_zone',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'capacity' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Relationship: A table can have many reservations over time.
     */

    public function reservation(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     *  Scope: To include only the tables that are active
     */

    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }


    /**
     * * Scope: To search for tables that can seat a specific number of guests
     */
    public function scopeHasCapacityFor(Builder $query, int $partySize): void
    {
        $query->where('capacity', '>=', $partySize);
    }
}
