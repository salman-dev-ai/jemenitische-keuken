<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class MenuItem extends Model
{
    use HasFactory, HasTranslations, SoftDeletes;

    public array $translatable = [
        'name',
        'description',
        'allergens',
    ];

    protected $fillable = [
        'menu_category_id',
        'name',
        'slug',
        'description',
        'allergens',
        'price',
        'image_path',
        'is_available',
        'is_featured',
        'is_spicy',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'is_available' => 'boolean',
            'is_featured' => 'boolean',
            'is_spicy' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(MenuCategory::class, 'menu_category_id');
    }

    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('is_available', true);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
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

    public function getLocalizedDescriptionAttribute(): ?string
    {
        // return $this->getTranslation(
        //     'description',
        //     app()->getLocale(),
        //     true,
        // ) ?: null;
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
}
