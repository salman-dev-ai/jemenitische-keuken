<?php

declare(strict_types=1);

return [
    'discount_type' => [
        'percentage' => 'Percentage',
        'fixed'      => 'Fixed amount',
    ],

    'coupon_scope' => [
        'all'        => 'Entire order',
        'products'   => 'Specific products',
        'categories' => 'Specific categories',
    ],

    'coupon_usage_status' => [
        'claimed'   => 'Claimed',
        'used'      => 'Used',
        'expired'   => 'Expired',
        'cancelled' => 'Cancelled',
    ],
];