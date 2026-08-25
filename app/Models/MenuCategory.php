<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'is_available' => 'boolean',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_available', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order');
    }

    public function menuItems(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'menu_category_id');
    }

    public function getLocalizedNameAttribute(): string
    {
        // return $this->getTranslation(
        //     'name',
        //     app()->getLocale(),
        //     true,
        // ) ?: '—';

        $locale = app()->getLocale();

        // استخدام getTranslation من Spatie لجلب النص بلغة محددة
        $translation = $this->getTranslation('name', $locale, false);

        // إذا وجدت الترجمة باللغة الحالية
        if (!empty($translation) && is_string($translation)) {
            return $translation;
        }

        // Fallback: جرب الإنجليزية
        $englishTranslation = $this->getTranslation('name', 'en', false);
        if (!empty($englishTranslation) && is_string($englishTranslation)) {
            return $englishTranslation;
        }
 return 'غير محدد';
    }

    public function getLocalizedDescriptionAttribute(): ?string
    {
        // if (blank($this->getRawOriginal('description'))) {
        //     return null;
        // }

        // return $this->getTranslation(
        //     'description',
        //     app()->getLocale(),
        //     true,
        // ) ?: null;

                  $locale = app()->getLocale();

        // استخدام getTranslation من Spatie لجلب النص بلغة محددة
        $translation = $this->getTranslation('description', $locale, false);

        // إذا وجدت الترجمة باللغة الحالية
        if (!empty($translation) && is_string($translation)) {
            return $translation;
        }

        // Fallback: جرب الإنجليزية
        $englishTranslation = $this->getTranslation('description', 'en', false);
        if (!empty($englishTranslation) && is_string($englishTranslation)) {
            return $englishTranslation;
        }
 return 'غير محدد';
    }
}
