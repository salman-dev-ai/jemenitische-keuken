<?php

declare(strict_types=1);

namespace App\Enums;

enum PaymentAttemptStatus: string
{
    case PENDING = 'pending';
    case PAID = 'paid';
    case FAILED = 'failed';
    case CANCELED = 'canceled';

    /**
     * تحويل حالة PayPro إلى حالتنا.
     * القاعدة الآمنة: لا نعتبر الدفعة مدفوعة إلا إذا كانت paid أو completed،
     * وأي حالة غير معروفة تبقى pending.
     */
    public static function fromProviderState(?string $state): self
    {
        return match (strtolower((string) $state)) {
            'paid', 'completed' => self::PAID,
            'failed', 'expired' => self::FAILED,
            'canceled', 'cancelled' => self::CANCELED,
            default => self::PENDING,
        };
    }
}
