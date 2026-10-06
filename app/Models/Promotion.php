<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

/**
 * نموذج العرض الترويجي — البطاقة التي تظهر للعميل في قسم التخفيضات.
 *
 * العلاقة: One-to-One مع Coupon.
 * - كل عرض مرتبط بكوبون واحد
 * - الكوبون قد يكون بدون عرض (يُستخدم بالكود فقط)
 * - لا يمكن إنشاء عرض بدون كوبون
 */
class Promotion extends Model
{
    use HasFactory;
    use HasTranslations;
    use SoftDeletes;

    protected $fillable = [
        'coupon_id',
        'image',
           'video', 
               'video_duration',  
 
        'title',
        'subtitle',
        'description',
        'cta_text',
        'starts_at',
        'ends_at',
        'sort_order',
        'is_active',
            'is_featured',     

    ];

    /**
     * الحقول المترجمة (ar/en/nl).
     */
    public array $translatable = [
        'title',
        'subtitle',
        'description',
        'cta_text',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'sort_order' => 'integer',
            'is_active' => 'boolean',

              'is_featured' => 'boolean',      
        'video_duration' => 'integer',   
        ];
    }

    // ══════════════════════════════════════════════════════════════
    // العلاقات
    // ══════════════════════════════════════════════════════════════

    /**
     * الكوبون المرتبط بالعرض (One-to-One).
     */
    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    // ══════════════════════════════════════════════════════════════
    // Scopes
    // ══════════════════════════════════════════════════════════════

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * العروض النشطة حاليًا (بين starts_at و ends_at).
     */
    public function scopeCurrentlyVisible(Builder $query): Builder
    {
        return $query->where(function (Builder $q): void {
            $q->whereNull('starts_at')
                ->orWhere('starts_at', '<=', now());
        })->where(function (Builder $q): void {
            $q->whereNull('ends_at')
                ->orWhere('ends_at', '>=', now());
        });
    }

    /**
     * العروض المرتبة.
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderByDesc('id');
    }

    /**
     * كل ما هو جاهز للعرض على العميل.
     */
    public function scopeVisible(Builder $query): Builder
    {
        return $query->active()->currentlyVisible()->ordered();
    }


    public function scopeFeatured(Builder $query): Builder
{
    return $query->where('is_featured', true);
}

public function scopeRegular(Builder $query): Builder
{
    return $query->where('is_featured', false);
}


    // ══════════════════════════════════════════════════════════════
    // Business Logic
    // ══════════════════════════════════════════════════════════════

    /**
     * هل العرض ظاهر الآن؟
     */
    public function isCurrentlyVisible(): bool
    {
        if (! $this->is_active) {
            return false;
        }

        if ($this->starts_at !== null && $this->starts_at->isFuture()) {
            return false;
        }

        if ($this->ends_at !== null && $this->ends_at->isPast()) {
            return false;
        }

        return true;
    }

    /**
     * هل العرض منتهي؟
     */
    public function isExpired(): bool
    {
        return $this->ends_at !== null && $this->ends_at->isPast();
    }

    /**
     * هل العرض لم يبدأ بعد؟
     */
    public function isUpcoming(): bool
    {
        return $this->starts_at !== null && $this->starts_at->isFuture();
    }

    /**
     * نسبة الخصم — للعرض في شارة البطاقة (مثلاً "20%").
     * تعيد null إذا كان الخصم مبلغًا ثابتًا.
     */
    public function discountBadge(): ?string
    {
        if ($this->coupon === null) {
            return null;
        }

        if (! $this->coupon->discount_type->isPercentage()) {
            return null;
        }

        return number_format((float) $this->coupon->discount_value, 0) . '%';
    }

    /**
     * الكوبون صالح للاستخدام؟ (فحص سريع)
     */
    public function hasValidCoupon(): bool
    {
        return $this->coupon !== null && $this->coupon->isValid();
    }


  
}