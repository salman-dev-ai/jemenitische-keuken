<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PaymentAttemptStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payment extends Model
{
    protected $fillable = [
        'order_id', 'provider', 'provider_payment_id', 'amount', 'currency',
        'status', 'provider_state', 'pay_method', 'checkout_url',
        'refunded_amount', 'failure_reason', 'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => PaymentAttemptStatus::class,
            'amount' => 'integer',
            'refunded_amount' => 'integer',
            'paid_at' => 'datetime',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function refunds(): HasMany
    {
        return $this->hasMany(Refund::class);
    }

    /** المبلغ المتاح للاسترداد (بالسنت). */
    public function refundableAmount(): int
    {
        return max(0, $this->amount - $this->refunded_amount);
    }
}
