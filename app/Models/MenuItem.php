<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class MenuItem extends Model
{
    use HasFactory, HasTranslations, SoftDeletes;

    /** 📐 ثابتَا سياسة العدد */
    public const FREE_DRINK_POLICY_PER_UNIT = 'per_unit';

    public const FREE_DRINK_POLICY_PER_LINE = 'per_line';

    public array $translatable = [
        'name',
        'description',
    ];

    protected $fillable = [
        'menu_category_id',
        'name',
        'slug',
        'description',
        'price',
        'image_path',
        'is_available',
        'is_featured',
        'is_spicy',
        'sort_order',

        'offers_free_drink',
        'free_drink_qty',
        'free_drink_policy',
        'free_drink_item_ids',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'is_available' => 'boolean',
            'is_featured' => 'boolean',
            'is_spicy' => 'boolean',
            'sort_order' => 'integer',

            'offers_free_drink' => 'boolean',
            'free_drink_qty' => 'integer',
            'free_drink_item_ids' => 'array',
        ];
    }

    /**
     *   سياسة تطبيق العدد (الافتراضي per_unit).
     */
    public function freeDrinkPolicy(): string
    {
        return $this->free_drink_policy === self::FREE_DRINK_POLICY_PER_LINE ? self::FREE_DRINK_POLICY_PER_LINE : self::FREE_DRINK_POLICY_PER_UNIT;
    }

    /**
     * 🔢 عدد الخانات المستحقة (Slots) لكمية محددة.
     *    - per_unit : العدد × الكمية
     *    - per_line : العدد ثابت مهما كانت الكمية
     */
    public function freeDrinkSlotsFor(int $quantity): int
    {
        $qty = max(1, (int) $this->free_drink_qty);

        return $this->freeDrinkPolicy() === self::FREE_DRINK_POLICY_PER_UNIT ? $qty * max(1, $quantity)
               : $qty;
    }

    /**
     * 🆔 الأصناف التي ضبطها المسؤول (بدون أي استعلام).
     *    تُرجِع [] إذا كان العرض مغلقاً، حتى لا تُعتبر بقايا المعرّفات أهلية.
     *
     * @return array<int, int>
     */
    public function configuredFreeDrinkIds(): array
    {
        if (! $this->offers_free_drink) {
            return [];
        }

        return array_values(array_unique(array_map('intval', (array) ($this->free_drink_item_ids ?? []))));
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(MenuCategory::class, 'menu_category_id');
    }

    /**
     * مسببات الحساسية والعلامات الغذائية المرتبطة بالطبق (متعدد لمتعدد).
     * تُدار من لوحة التحكم عبر قاموس Allergen — لا تُخزَّن أسماء كنص حر.
     */
    public function allergens(): BelongsToMany
    {
        return $this->belongsToMany(Allergen::class, 'allergen_menu_item')->withTimestamps();
    }

    /**
     * العناصر المفعّلة فقط مرتّبة — الجاهزة للعرض في واجهة العملاء.
     */
    public function activeAllergens(): BelongsToMany
    {
        return $this->allergens()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('slug');
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
        $locale = app()->getLocale();

        $translation = $this->getTranslation('name', $locale, false);
        if (! empty($translation) && is_string($translation)) {
            return $translation;
        }

        $fallback = $this->getTranslation('name', 'en', false);

        return (! empty($fallback) && is_string($fallback)) ? $fallback : '—';
    }

    public function getLocalizedDescriptionAttribute(): ?string
    {
        $locale = app()->getLocale();

        $translation = $this->getTranslation('description', $locale, false);
        if (! empty($translation) && is_string($translation)) {
            return $translation;
        }

        $fallback = $this->getTranslation('description', 'en', false);

        return (! empty($fallback) && is_string($fallback)) ? $fallback : null;
    }
}
