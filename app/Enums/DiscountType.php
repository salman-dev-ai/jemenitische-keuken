<?php

declare(strict_types=1);

/**
 * نوع الخصم المطبَّق على المنتجات أو الطلبات (نسبة / مبلغ ثابت).
 * يُستخدم في Filament Forms كـ Select وفي حسابات السلة (Cart Service).
 * يطبّق HasLabel و HasColor لتكامل مباشر مع Filament 5 Badges.
 */

namespace App\Enums;

use App\Enums\Concerns\HasEnumHelpers;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum DiscountType: string implements HasColor, HasLabel
{
    use HasEnumHelpers;

    case Percentage = 'percentage';
    case Fixed      = 'fixed';

    /**
     * التسمية المترجمة — تُعرض في Filament Tables و Forms.
     */
    public function getLabel(): string
    {
        return match ($this) {
            self::Percentage => __('enums.discount_type.percentage'),
            self::Fixed      => __('enums.discount_type.fixed'),
        };
    }

    /**
     * لون الـ Badge في Filament — نسبة = أزرق، مبلغ ثابت = أخضر.
     */
    public function getColor(): string
    {
        return match ($this) {
            self::Percentage => 'info',
            self::Fixed      => 'success',
        };
    }


        /**
     * هل هذا النوع نسبي (يحتاج حساب نسبة)؟
     */
    public function isPercentage(): bool
    {
        return $this === self::Percentage;
    }

    /**
     * هل هذا النوع مبلغ ثابت؟
     */
    public function isFixed(): bool
    {
        return $this === self::Fixed;
    }

    /**
     * حساب قيمة الخصم الفعلية بناءً على المبلغ.
     * القيمة النهائية لا تتجاوز المبلغ الأصلي أبدًا.
     */
    public function apply(float $amount, float $discountValue): float
    {
        $discount = match ($this) {
            self::Percentage => $amount * ($discountValue / 100),
            self::Fixed      => $discountValue,
        };

        return min($discount, $amount);
    }

    /**
     * الحد الأقصى للقيمة — لمنع 150% مثلاً.
     */
    public function maxValue(): float
    {
        return match ($this) {
            self::Percentage => 100.0,
            self::Fixed      => 99999999.99,
        };
    }
}