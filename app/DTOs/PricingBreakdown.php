<?php

declare(strict_types=1);

namespace App\DTOs;

/**
 * تفصيل حساب السلة/الطلب.
 *
 * الترتيب:
 *   subtotal (قبل الخصم)
 *     - discount
 *     = taxable_amount
 *     + vat (vat_rate على taxable_amount)
 *     = total
 */
final readonly class PricingBreakdown
{
    public function __construct(
        public float $subtotal,
        public float $discount,
        public float $taxableAmount,
        public float $vatRate,
        public float $vat,
        public float $total,
    ) {}

    /**
     * @return array<string, float>
     */
    public function toArray(): array
    {
        return [
            'subtotal' => $this->subtotal,
            'discount' => $this->discount,
            'taxable_amount' => $this->taxableAmount,
            'vat_rate' => $this->vatRate,
            'vat' => $this->vat,
            'total' => $this->total,
        ];
    }
}