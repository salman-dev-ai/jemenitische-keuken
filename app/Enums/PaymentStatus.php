<?php

declare(strict_types=1);

namespace App\Enums;

enum PaymentStatus: string
{
    case PENDING = 'pending';
    case UNPAID = 'unpaid';
    case PAID = 'paid';
    case FAILED = 'failed';
    case REFUNDED = 'refunded';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'بانتظار الدفع',
            self::UNPAID => 'غير مدفوع',
            self::PAID => 'مدفوع',
            self::FAILED => 'فشل الدفع',
            self::REFUNDED => 'مسترجع',
        };
    }
}
