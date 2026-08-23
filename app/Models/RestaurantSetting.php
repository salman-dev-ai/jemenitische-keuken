<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class RestaurantSetting extends Model
{
    use HasFactory, HasTranslations;

    public array $translatable = ['name', 'address'];

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

    protected function casts(): array
    {
        return [
            'opening_hours' => 'array',
            'accepts_reservations' => 'boolean',
            'accepts_online_orders' => 'boolean',
        ];
    }

    public function getLocalizedNameAttribute(): string
    {
        $locale = app()->getLocale();

        return $this->name[$locale] ?? $this->name['en'] ?? $this->name['ar'] ?? '';
    }

    public function getLocalizedAddressAttribute(): ?string
    {
        if (! is_array($this->address)) {
            return $this->address;
        }
        $locale = app()->getLocale();

        return $this->address[$locale] ?? $this->address['ar'] ?? $this->address['en'] ?? null;
    }
}
