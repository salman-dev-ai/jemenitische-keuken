<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;




/**
 * Responsibility: Manages the core settings, location, and global toggles for the   restaurant  .
 */
class RestaurantSetting extends Model
{

    use HasTranslations;

    /** The attributes that are translatable.
     *
     * @var array< string>
     */
    public array $translatable = ['name'];

    /** The attributes that are mass assignable
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'phone',
        'whatsapp',
        'email',
         'address',
        'city',
        'postal_code',
        'google_maps_link',
        'opening_hours',
        'accepts_reservations',
        'accepts_online_orders',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */

    protected function casts(): array
    {

        return [
            'opening_hours' => 'array',
            'accepts_reservations' => 'boolean',
            'accepts_online_orders' => 'boolean'
        ];
    }
}
