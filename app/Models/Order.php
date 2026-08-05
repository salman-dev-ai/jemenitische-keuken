<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Order extends Model
{

use HasFactory;
    /**
     * Responsibility: Manages different types of orders (e.g., dine-in, takeout).
     */

    protected $fillable = [
        'order_number',
        'customer_name',
        'customer_phone',
        'customer_email',
        'type',
        'status',
        'subtotal',
        'tax',
        'total',
        'payment_status',
        'payment_method',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'type' => OrderType::class,
            'status' => OrderStatus::class,
            'subtotal' => 'decimal:2',
            'tax' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }

    /**
     *   Auto-generate unique order number
     */
    protected static function booted(): void
    {
        static::creating(function (Order $order) {
            if (empty($order->order_number)) {

                $order->order_number = 'ORD-' . strtoupper(Str::random(5));
            }
        });
    }

    /**
     * Relationship: An order has many items.
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }


    /**
     * Scope: للحصول على الطلبات غير المكتملة (التي تحتاج إلى تحضير)
     */
    public function scopeActive(Builder $query): void
    {
        $query -> whereIn('status', [OrderStatus::PENDING, OrderStatus::PROCESSING]);
    }
}
