<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Slider extends Model
{
    protected $fillable = [
        'image',
        'eyebrow',
        'title',
        'subtitle',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'eyebrow'   => 'array',
            'title'     => 'array',
            'subtitle'  => 'array',
            'is_active' => 'boolean',
        ];
    }

    /**
     * الشرائح النشطة فقط
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * ترتيب حسب الأحدث
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderByDesc('created_at');
    }

    /**
     * الحصول على الترجمة حسب اللغة الحالية (اختياري)
     */
    public function getTranslation(string $field, ?string $locale = null): ?string
    {
        $locale = $locale ?? app()->getLocale();
        $value = $this->{$field};

        return is_array($value) ? ($value[$locale] ?? null) : $value;
    }
}
