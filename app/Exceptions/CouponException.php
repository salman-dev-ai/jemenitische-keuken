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
     * @param  array<string, mixed>  $context
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
            __('coupons.errors.not_found', ['code' => $code]),
            ['code' => $code],
        );
    }

    public static function inactive(): self
    {
        return new self(self::CODE_INACTIVE, __('coupons.errors.inactive'));
    }

    public static function expired(): self
    {
        return new self(self::CODE_EXPIRED, __('coupons.errors.expired'));
    }

    public static function exhausted(): self
    {
        return new self(self::CODE_EXHAUSTED, __('coupons.errors.exhausted'));
    }

    public static function customerLimit(int $maxPerCustomer): self
    {
        return new self(
            self::CODE_CUSTOMER_LIMIT,
            __('coupons.errors.customer_limit', ['max' => $maxPerCustomer]),
            ['max_uses_per_customer' => $maxPerCustomer],
        );
    }

    public static function minTotal(float $minTotal, float $currentTotal): self
    {
        return new self(
            self::CODE_MIN_TOTAL,
            __('coupons.errors.min_total', ['min_total' => $minTotal]),
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
            __('coupons.errors.not_applicable'),
        );
    }

    public static function alreadyClaimed(): self
    {
        return new self(
            self::CODE_ALREADY_CLAIMED,
            __('coupons.errors.already_claimed'),
        );
    }

    public static function alreadyUsed(): self
    {
        return new self(
            self::CODE_ALREADY_USED,
            __('coupons.errors.already_used'),
        );
    }
}
