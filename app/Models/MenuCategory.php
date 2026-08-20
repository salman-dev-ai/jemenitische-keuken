<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Translatable\HasTranslations;

class MenuCategory extends Model
{
    use HasFactory, HasTranslations;

    public array $translatable = [
        'name',
        'description',
    ];

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image_path',
        'sort_order',
        'is_available',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_available' => 'boolean',
    ];

    /**
     * نطاق لجلب الأقسام المفعلة فقط
     * مرتبة حسب أولوية العرض.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query
            ->where('is_available', true)
            ->orderBy('sort_order', 'asc');
    }

    /**
     * استرجاع الاسم حسب لغة المستخدم الحالية.
     */
    public function getLocalizedNameAttribute(): string
    {
        $locale = app()->getLocale();

        return $this->name[$locale]
            ?? $this->name['ar']
            ?? $this->name['en']
            ?? '';
    }

    /**
     * استرجاع الوصف حسب لغة المستخدم الحالية.
     */
    public function getLocalizedDescriptionAttribute(): ?string
    {
        $locale = app()->getLocale();

        return $this->description[$locale]
            ?? $this->description['ar']
            ?? $this->description['en']
            ?? null;
    }

    /**
     * علاقة الأصناف التابعة لهذا القسم.
     */
    public function menuItems(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'menu_category_id');
    }
}


