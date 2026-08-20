<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;

/**
 * Responsibility: Represents a specific dish or drink, handling pricing, translations, and category association.
 */
class MenuItem extends Model
{
    // دمج الـ Traits الخاصة بالترجمة والحذف الآمن
    use HasTranslations, SoftDeletes;

    use HasFactory;
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

    /**
     * Relationship: A Menu Item belongs to one Category
     */

    public function category(): BelongsTo
    {
        return $this->belongsTo(MenuCategory::class, 'menu_category_id');
    }



    /**
     * Scope: للحصول فقط على الأطباق المتاحة للطلب حالياً
     */
    public function scopeAvailable(Builder $query): void
    {
        $query->where('is_available', true);
    }


    /**
     * scope:To find signature dishes to feature on the homepage
     */

    // بدلاً من أن تكتب أوامر قاعدة البيانات المعقدة والصعبة بلغة SQL يدوياً، يتيح لك الـ Builder كتابتها بأكواد
    // PHP سهلة ونظيفة مثل (where, orderBy, get)، وهو يتولى تحويلها خلف الكواليس إلى لغة تفهمها قاعدة البيانات.
    public function scopeFeatured(Builder $query): void
    {
        $query->where('is_featured', true);
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
