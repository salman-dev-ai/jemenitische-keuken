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

    public array $translatable = ['name', 'description', 'allergens'];

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

    // ✅ تعديل نوع الـ return إلى Builder بدلاً من void
    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('is_available', true);
    }

    // ✅ تعديل نوع الـ return إلى Builder بدلاً من void
    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function getLocalizedNameAttribute(): string
    {
        $locale = app()->getLocale();

        return $this->name[$locale] ?? $this->name['ar'] ?? $this->name['en'] ?? '';
    }

    public function getLocalizedDescriptionAttribute(): ?string
    {
        $locale = app()->getLocale();

        return $this->description[$locale] ?? $this->description['ar'] ?? $this->description['en'] ?? null;
    }
}
