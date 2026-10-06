<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\CouponUsageStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * نموذج سجل استخدام الكوبون.
 */
class CouponUsage extends Model
{
    protected $fillable = [
        'coupon_id',
        'customer_id',
        'order_id',
        'status',
        'claimed_at',
        'used_at',
        'discount_amount',
    ];

    protected function casts(): array
    {
        return [
            'status' => CouponUsageStatus::class,
            'claimed_at' => 'datetime',
            'used_at' => 'datetime',
            'discount_amount' => 'decimal:2',
        ];
    }

    // ═══ العلاقات ═══

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    // ═══ Scopes ═══

    public function scopeClaimed(Builder $query): Builder
    {
        return $query->where('status', CouponUsageStatus::Claimed);
    }

    public function scopeUsed(Builder $query): Builder
    {
        return $query->where('status', CouponUsageStatus::Used);
    }

    // ═══ Business Logic ═══

    /**
     * هل الكوبون ما زال قابلًا للاستخدام؟
     */
    public function isUsable(): bool
    {
        return $this->status === CouponUsageStatus::Claimed;
    }

    /**
     * تعليم الكوبون كمُستخدم في طلب.
     */
    public function markAsUsed(?int $orderId = null, ?float $discountAmount = null): void
    {
        $this->update([
            'status' => CouponUsageStatus::Used,
            'order_id' => $orderId,
            'discount_amount' => $discountAmount,
            'used_at' => now(),
        ]);
    }

    /**
     * تحرير الكوبون (عند إلغاء الطلب).
     */
    public function release(): void
    {
        $this->update([
            'status' => CouponUsageStatus::Cancelled,
        ]);
    }
}