<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\DiscountType;
use App\Enums\OrderStatus;
use App\Enums\OrderType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * نموذج الطلب.
 *
 * 🔗 العلاقات: items, reservation, customer, coupon
 * 💰 الحقول المالية: subtotal, tax, total, discount_amount
 * 🎫 snapshot الكوبون: coupon_id, coupon_code, discount_type, discount_amount
 */
class Order extends Model
{
    use HasFactory;

    /**
     * الحقول القابلة للتعيين الجماعي.
     *
     * ⚠️ ملاحظة: coupon_id, coupon_code, discount_type, discount_amount
     *    تُملأ عبر OrderService فقط (snapshot وقت الطلب).
     */
    protected $fillable = [
        // ─── العلاقات ───
        'reservation_id',
        'customer_id',

        // ─── بيانات الطلب ───
        'order_number',
        'type',
        'status',

        // ─── بيانات العميل (snapshot) ───
        'customer_name',
        'customer_phone',
        'customer_email',

        // ─── عنوان التوصيل ───
        'delivery_address',
        'delivery_city',
        'delivery_postal_code',

        // ─── المبالغ المالية ───
        'subtotal',
        'tax',
        'total',

        // ─── الدفع ───
        'payment_status',
        'payment_method',

        // ─── الكوبون (snapshot) ───
        'coupon_id',
        'coupon_code',
        'discount_type',
        'discount_amount',

        // ─── ملاحظات ───
        'notes',
    ];

    /**
     * تحويلات الأنواع — Laravel 11+ style.
     *
     * @return array<string, mixed>
     */
    protected function casts(): array
    {
        return [
            // ─── Enums ───
            'type'           => OrderType::class,
            'status'         => OrderStatus::class,
            'discount_type'  => DiscountType::class,

            // ─── المبالغ المالية (دقة عشرية) ───
            'subtotal'        => 'decimal:2',
            'tax'             => 'decimal:2',
            'total'           => 'decimal:2',
            'discount_amount' => 'decimal:2',
        ];
    }

    /**
     * 🎬 أحداث النموذج.
     *
     * توليد order_number تلقائياً عند الإنشاء إن لم يُمرَّر.
     */
    protected static function booted(): void
    {
        static::creating(function (Order $order): void {
            if (empty($order->order_number)) {
                $order->order_number = 'ORD-' . strtoupper(Str::random(5));
            }
        });
    }

    // ══════════════════════════════════════════════════════════════
    // العلاقات
    // ══════════════════════════════════════════════════════════════

    /**
     * عناصر الطلب.
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * الحجز المرتبط بالطلب (إن وُجد).
     */
    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }

    /**
     * العميل المرتبط بالطلب (إن وُجد).
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * الكوبون المستخدم في الطلب (إن وُجد).
     *
     * ⚠️ ملاحظة: حتى لو حُذف الكوبون، تبقى snapshot القيم
     *    في حقول coupon_code, discount_type, discount_amount.
     */
    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    // ══════════════════════════════════════════════════════════════
    // Scopes
    // ══════════════════════════════════════════════════════════════

    /**
     * الطلبات النشطة (قيد المعالجة).
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->whereIn('status', [
            OrderStatus::PENDING,
            OrderStatus::PROCESSING,
        ]);
    }

    // ══════════════════════════════════════════════════════════════
    // Business Helpers
    // ══════════════════════════════════════════════════════════════

    /**
     * هل الطلب استخدم كوبون؟
     */
    public function hasCoupon(): bool
    {
        return $this->coupon_id !== null;
    }

    /**
     * المبلغ قبل الخصم (للعرض في الملخص).
     */
    public function subtotalBeforeDiscount(): float
    {
        return (float) $this->subtotal;
    }

    /**
     * المبلغ بعد الخصم وقبل الضريبة.
     */
    public function taxableAmount(): float
    {
        return round((float) $this->subtotal - (float) $this->discount_amount, 2);
    }
}
