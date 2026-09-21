<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class OrderOption extends Model
{
    protected $fillable = [
        'image',
        'title',
        'description',
        'icon',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'title'       => 'array',
            'description' => 'array',
            'is_active'   => 'boolean',
        ];
    }

    /**
     * فلتر محلي (Scope) لجلب الخيارات النشطة فقط
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
