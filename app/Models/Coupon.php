<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\CouponScope;
use App\Enums\DiscountType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

/**
 * نموذج الكوبون — قلب قسم التخفيضات.
 *
 * 🔗 العلاقات: promotion, usages, menuItems, menuCategories
 * 🌐 الترجمات: name, description (ar/en/nl)
 * 🎯 النطاق: applies_to (all / products / categories)
 * 🆕 حقل is_welcome: كوبونات ترحيبية للعميل الجديد
 */
class Coupon extends Model
{
    use HasFactory;
    use HasTranslations;
    use SoftDeletes;

    /**
     * الحقول القابلة للتعيين الجماعي.
     */
    protected $fillable = [
        'code',
        'name',
        'description',
        'discount_type',
        'discount_value',
        'applies_to',
        'expires_at',
        'min_order_total',
        'max_uses',
        'max_uses_per_customer',
        'used_count',
        'is_active',
        'is_welcome',
    ];

    /**
     * الحقول المترجمة (spatie/laravel-translatable).
     *
     * @var array<int, string>
     */
    public array $translatable = ['name', 'description'];

    /**
     * تحويلات الأنواع — Laravel 11+ style.
     *
     * @return array<string, mixed>
     */
    protected function casts(): array
    {
        return [
            // ─── Enums ───
            'discount_type' => DiscountType::class,
            'applies_to'    => CouponScope::class,

            // ─── المبالغ المالية ───
            'discount_value'  => 'decimal:2',
            'min_order_total' => 'decimal:2',

            // ─── التواريخ ───
            'expires_at' => 'datetime',

            // ─── الأعداد ───
            'max_uses'              => 'integer',
            'max_uses_per_customer' => 'integer',
            'used_count'            => 'integer',

            // ─── منطقية ───
            'is_active'  => 'boolean',
            'is_welcome' => 'boolean',
        ];
    }

    // ══════════════════════════════════════════════════════════════
    // العلاقات
    // ══════════════════════════════════════════════════════════════

    /**
     * العرض التسويقي المرتبط بالكوبون (One-to-One).
     */
    public function promotion(): HasOne
    {
        return $this->hasOne(Promotion::class);
    }

    /**
     * سجل استخدامات الكوبون.
     */
    public function usages(): HasMany
    {
        return $this->hasMany(CouponUsage::class);
    }

    /**
     * المنتجات المشمولة (عند applies_to = 'products').
     */
    public function menuItems(): BelongsToMany
    {
        return $this->belongsToMany(
            MenuItem::class,
            'coupon_product',
            'coupon_id',
            'menu_item_id',
        );
    }

    /**
     * التصنيفات المشمولة (عند applies_to = 'categories').
     */
    public function menuCategories(): BelongsToMany
    {
        return $this->belongsToMany(
            MenuCategory::class,
            'coupon_category',
            'coupon_id',
            'menu_category_id',
        );
    }

    // ══════════════════════════════════════════════════════════════
    // Scopes
    // ══════════════════════════════════════════════════════════════

    /**
     * الكوبونات المُفعَّلة فقط.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * الكوبونات غير المنتهية الصلاحية.
     */
    public function scopeNotExpired(Builder $query): Builder
    {
        return $query->where(function (Builder $q): void {
            $q->whereNull('expires_at')
                ->orWhere('expires_at', '>', now());
        });
    }

    /**
     * الكوبونات التي لم تصل للحد الأقصى للاستخدام.
     */
    public function scopeNotExhausted(Builder $query): Builder
    {
        return $query->where(function (Builder $q): void {
            $q->whereNull('max_uses')
                ->orWhereColumn('used_count', '<', 'max_uses');
        });
    }

    /**
     * الكوبونات المتاحة للاستخدام (active + not expired + not exhausted).
     */
    public function scopeAvailable(Builder $query): Builder
    {
        return $query->active()->notExpired()->notExhausted();
    }

    /**
     * 🆕 الكوبونات الترحيبية فقط.
     */
    public function scopeWelcome(Builder $query): Builder
    {
        return $query->where('is_welcome', true);
    }

    // ══════════════════════════════════════════════════════════════
    // Business Logic
    // ══════════════════════════════════════════════════════════════

    /**
     * هل انتهت صلاحية الكوبون؟
     */
    public function isExpired(): bool
    {
        return $this->expires_at !== null && $this->expires_at->isPast();
    }

    /**
     * هل وصل الكوبون للحد الأقصى للاستخدام الكلي؟
     */
    public function hasReachedMaxUses(): bool
    {
        return $this->max_uses !== null && $this->used_count >= $this->max_uses;
    }

    /**
     * هل الكوبون صالح للاستخدام الآن؟
     */
    public function isValid(): bool
    {
        return $this->is_active
            && ! $this->isExpired()
            && ! $this->hasReachedMaxUses();
    }

    /**
     * هل يحقق إجمالي الطلب الحد الأدنى المطلوب؟
     */
    public function meetsMinimumTotal(float $orderTotal): bool
    {
        return $orderTotal >= (float) $this->min_order_total;
    }

    /**
     * حساب قيمة الخصم بناءً على إجمالي الطلب.
     *
     * ⚠️ ملاحظة: هذا حساب مبسّط يستخدم orderTotal فقط.
     *    للحساب الكامل مع نطاق المنتجات/التصنيفات،
     *    استخدم CouponService::validate().
     */
    public function calculateDiscount(float $orderTotal): float
    {
        if (! $this->isValid() || ! $this->meetsMinimumTotal($orderTotal)) {
            return 0.0;
        }

        return $this->discount_type->apply(
            $orderTotal,
            (float) $this->discount_value,
        );
    }

    /**
     * زيادة عدد مرات الاستخدام بمقدار 1 (atomic).
     */
    public function incrementUsage(): void
    {
        $this->increment('used_count');
    }

    /**
     * هل الكوبون مربوط بمنتجات/تصنيفات محددة؟
     */
    public function hasScope(): bool
    {
        return $this->applies_to->requiresRelation();
    }
}
