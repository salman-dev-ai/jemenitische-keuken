<?php

declare(strict_types=1);

namespace App\DTOs;

use App\Models\Coupon;

/**
 * نتيجة التحقق من الكوبون وتطبيقه.
 */
final readonly class CouponResult
{
    private function __construct(
        public bool $isValid,
        public float $discountAmount,
        public ?Coupon $coupon,
        public ?string $errorCode,
        public ?string $errorMessage,
    ) {}

    public static function success(Coupon $coupon, float $discountAmount): self
    {
        return new self(
            isValid: true,
            discountAmount: round($discountAmount, 2),
            coupon: $coupon,
            errorCode: null,
            errorMessage: null,
        );
    }

    public static function failure(string $errorCode, string $errorMessage): self
    {
        return new self(
            isValid: false,
            discountAmount: 0.0,
            coupon: null,
            errorCode: $errorCode,
            errorMessage: $errorMessage,
        );
    }

    public static function none(): self
    {
        return new self(
            isValid: false,
            discountAmount: 0.0,
            coupon: null,
            errorCode: null,
            errorMessage: null,
        );
    }

    public function hasCoupon(): bool
    {
        return $this->coupon !== null;
    }

    public function hasError(): bool
    {
        return $this->errorCode !== null;
    }
}