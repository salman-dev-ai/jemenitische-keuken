<?php

declare(strict_types=1);

/**
 * ═══════════════════════════════════════════════════════════════════════════
 * 📄 Path: lang/en/coupons.php
 * ═══════════════════════════════════════════════════════════════════════════
 *
 * 🎯 Purpose:
 *    Coupon error translations — used in CouponException and OrderService.
 *    Each key maps to a user-facing error message.
 *
 * 🧩 Depends on:
 *    - App\Exceptions\CouponException
 *    - App\Services\OrderService
 *
 * 🕒 Last updated: 2026-10-15
 * ═══════════════════════════════════════════════════════════════════════════
 */

return [
    'errors' => [
        // ─── CouponException ───
        'not_found' => 'Coupon code ":code" not found.',
        'inactive' => 'This coupon is inactive.',
        'expired' => 'This coupon has expired.',
        'exhausted' => 'This coupon has reached its maximum uses.',
        'customer_limit' => 'You have reached the maximum allowed uses (:max) for this coupon.',
        'min_total' => 'Order total must be at least €:min_total.',
        'not_applicable' => 'This coupon is not applicable to any item in your cart.',
        'already_claimed' => 'You have already claimed this coupon.',
        'already_used' => 'This coupon has already been used.',

        // ─── OrderService ───
        'invalid_code' => 'Invalid coupon code.',
        'customer_required' => 'You must be logged in to apply a coupon.',
        'customer_not_found' => 'Customer account not found.',
    ],
];
