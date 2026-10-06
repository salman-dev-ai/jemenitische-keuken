<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

/**
 * استثناء مخصّص لأخطاء الكوبونات.
 */
class CouponException extends Exception
{
    public const CODE_NOT_FOUND = 'coupon_not_found';
    public const CODE_INACTIVE = 'coupon_inactive';
    public const CODE_EXPIRED = 'coupon_expired';
    public const CODE_EXHAUSTED = 'coupon_exhausted';
    public const CODE_CUSTOMER_LIMIT = 'coupon_customer_limit';
    public const CODE_MIN_TOTAL = 'coupon_min_total';
    public const CODE_NOT_APPLICABLE = 'coupon_not_applicable';
    public const CODE_ALREADY_CLAIMED = 'coupon_already_claimed';
    public const CODE_ALREADY_USED = 'coupon_already_used';

    public readonly string $errorCode;

    /** @var array<string, mixed> */
    public readonly array $context;

    /**
     * @param array<string, mixed> $context
     */
    public function __construct(
        string $errorCode,
        string $message = '',
        array $context = [],
        ?Exception $previous = null,
    ) {
        $this->errorCode = $errorCode;
        $this->context = $context;

        parent::__construct(
            $message !== '' ? $message : $errorCode,
            0,
            $previous,
        );
    }

    public static function notFound(string $code): self
    {
        return new self(
            self::CODE_NOT_FOUND,
            "Coupon code '{$code}' not found.",
            ['code' => $code],
        );
    }

    public static function inactive(): self
    {
        return new self(self::CODE_INACTIVE, 'Coupon is inactive.');
    }

    public static function expired(): self
    {
        return new self(self::CODE_EXPIRED, 'Coupon has expired.');
    }

    public static function exhausted(): self
    {
        return new self(self::CODE_EXHAUSTED, 'Coupon has reached its maximum uses.');
    }

    public static function customerLimit(int $maxPerCustomer): self
    {
        return new self(
            self::CODE_CUSTOMER_LIMIT,
            "Customer has reached the limit of {$maxPerCustomer} uses.",
            ['max_uses_per_customer' => $maxPerCustomer],
        );
    }

    public static function minTotal(float $minTotal, float $currentTotal): self
    {
        return new self(
            self::CODE_MIN_TOTAL,
            "Order total must be at least {$minTotal}.",
            [
                'min_order_total' => $minTotal,
                'current_total' => $currentTotal,
            ],
        );
    }

    public static function notApplicable(): self
    {
        return new self(
            self::CODE_NOT_APPLICABLE,
            'Coupon is not applicable to any item in the cart.',
        );
    }

    public static function alreadyClaimed(): self
    {
        return new self(
            self::CODE_ALREADY_CLAIMED,
            'Coupon already claimed by this customer.',
        );
    }

    public static function alreadyUsed(): self
    {
        return new self(
            self::CODE_ALREADY_USED,
            'Coupon already used.',
        );
    }
}