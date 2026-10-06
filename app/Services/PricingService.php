<?php

declare(strict_types=1);

namespace App\Services;

use App\DTOs\PricingBreakdown;
use App\Models\RestaurantSetting;

/**
 * خدمة حساب الأسعار.
 *
 * القاعدة:
 *   - أسعار المنتجات لا تشمل الضريبة
 *   - الخصم يُطبَّق أولًا
 *   - الضريبة تُحسب على (subtotal - discount)
 *
 * يستخدمها Cart و Order و Filament لعرض نفس الأرقام.
 */
class PricingService
{
    public function __construct(
        private readonly RestaurantSetting $settings,
    ) {}

    /**
     * حساب تفصيل التسعير.
     *
     * @param float $subtotal إجمالي المنتجات قبل الخصم
     * @param float $discount قيمة الخصم (0 إذا لا كوبون)
     */
    public function calculate(float $subtotal, float $discount = 0.0): PricingBreakdown
    {
        // حماية: الخصم لا يتجاوز الإجمالي
        $discount = max(0.0, min($discount, $subtotal));

        // المبلغ الخاضع للضريبة
        $taxableAmount = round($subtotal - $discount, 2);

        // نسبة الضريبة من إعدادات المطعم
        $vatRate = (float) ($this->settings->vat_rate ?? 0.0);

        // حساب الضريبة
        $vat = round($taxableAmount * ($vatRate / 100), 2);

        // الإجمالي النهائي
        $total = round($taxableAmount + $vat, 2);

        return new PricingBreakdown(
            subtotal: round($subtotal, 2),
            discount: round($discount, 2),
            taxableAmount: $taxableAmount,
            vatRate: $vatRate,
            vat: $vat,
            total: $total,
        );
    }
}