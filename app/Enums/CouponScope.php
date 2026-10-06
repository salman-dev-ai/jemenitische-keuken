<?php

declare(strict_types=1);

/**
 * نطاق تطبيق الكوبون (الطلب كامل / منتجات / تصنيفات).
 * يحدّده الأدمن عند الإنشاء، ويُستخدم في CouponService لحساب المبلغ المؤهل.
 * يطبّق HasLabel و HasColor لتكامل مباشر مع Filament 5.
 */

namespace App\Enums;

use App\Enums\Concerns\HasEnumHelpers;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum CouponScope: string implements HasColor, HasLabel
{
    use HasEnumHelpers;

    case All        = 'all';
    case Products   = 'products';
    case Categories = 'categories';

    /**
     * التسمية المترجمة — تُعرض في Filament Tables و Forms.
     */
    public function getLabel(): string
    {
        return match ($this) {
            self::All        => __('enums.coupon_scope.all'),
            self::Products   => __('enums.coupon_scope.products'),
            self::Categories => __('enums.coupon_scope.categories'),
        };
    }

    /**
     * لون الـ Badge في Filament.
     */
    public function getColor(): string
    {
        return match ($this) {
            self::All        => 'success',
            self::Products   => 'info',
            self::Categories => 'warning',
        };
    }

    /**
     * هل الكوبون يحتاج ربطًا بمنتجات/تصنيفات؟
     */
    public function requiresRelation(): bool
    {
        return $this !== self::All;
    }

    /**
     * اسم العلاقة في Model Coupon.
     */
    public function relationName(): ?string
    {
        return match ($this) {
            self::All        => null,
            self::Products   => 'menuItems',
            self::Categories => 'menuCategories',
        };
    }
}