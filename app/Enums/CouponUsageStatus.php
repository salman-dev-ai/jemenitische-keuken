<?php

declare(strict_types=1);

/**
 * حالة استخدام الكوبون من قِبَل العميل.
 * - Claimed:   حصل على الكوبون ولم يستخدمه بعد
 * - Used:      استخدمه في طلب مؤكَّد
 * - Expired:   انتهت صلاحيته وهو في حساب العميل
 * - Cancelled: أُلغي الطلب فحُرِّر الكوبون
 * يطبّق HasLabel و HasColor لتكامل مباشر مع Filament 5.
 */

namespace App\Enums;

use App\Enums\Concerns\HasEnumHelpers;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum CouponUsageStatus: string implements HasColor, HasLabel
{
    use HasEnumHelpers;

    case Claimed   = 'claimed';
    case Used      = 'used';
    case Expired   = 'expired';
    case Cancelled = 'cancelled';

    /**
     * التسمية المترجمة.
     */
    public function getLabel(): string
    {
        return match ($this) {
            self::Claimed   => __('enums.coupon_usage_status.claimed'),
            self::Used      => __('enums.coupon_usage_status.used'),
            self::Expired   => __('enums.coupon_usage_status.expired'),
            self::Cancelled => __('enums.coupon_usage_status.cancelled'),
        };
    }

    /**
     * لون الـ Badge في Filament.
     */
    public function getColor(): string
    {
        return match ($this) {
            self::Claimed   => 'info',
            self::Used      => 'success',
            self::Expired   => 'warning',
            self::Cancelled => 'danger',
        };
    }

    /**
     * هل يمكن إعادة استخدام الكوبون من نفس العميل؟
     */
    public function isReusable(): bool
    {
        return in_array($this, [self::Cancelled, self::Expired], true);
    }

    /**
     * هل الحالة نهائية (لا تتغير)؟
     */
    public function isFinal(): bool
    {
        return $this === self::Used;
    }
}