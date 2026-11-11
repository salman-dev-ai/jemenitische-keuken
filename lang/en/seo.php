<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| SEO copy (English)
|--------------------------------------------------------------------------
| Used as a fallback when no custom meta title or meta description is set in
| the admin panel (Filament). Keep the key structure identical to
| lang/nl/seo.php and lang/ar/seo.php.
|
| - pages.home            → the home page
| - pages.restaurant-info → page with opening hours, allergens and delivery
| - pages.delivery-zones  → page with delivery areas
| - pages.fallback        → generic fallback for unknown pages
*/

return [

    'pages' => [

        'home' => [
            'title' => 'Yemeni Kitchen Amsterdam | Order Mandi & traditional dishes',
            'description' => 'Authentic Yemeni cuisine in Amsterdam. Order online or book a table: mandi, haneeth and more. Delivery, pickup or dine-in.',
        ],

        'restaurant-info' => [
            'title' => 'Restaurant information | Opening hours, allergens and delivery',
            'description' => 'View the opening hours, allergen information and delivery areas of Yemeni Kitchen in Amsterdam in one overview.',
        ],

        'delivery-zones' => [
            'title' => 'Delivery areas and delivery costs',
            'description' => 'View our delivery areas, postal codes, delivery costs and the minimum order amount in Amsterdam.',
        ],

        'fallback' => [
            'title' => 'Yemeni Kitchen Amsterdam',
            'description' => 'Authentic Yemeni dishes in Amsterdam. Order online or book a table.',
        ],

    ],

];
