<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| SEO-teksten (Nederlands) — de standaardtaal van de site
|--------------------------------------------------------------------------
| Deze teksten worden gebruikt als er in de administratie (Filament) geen
| eigen meta-titel of meta-omschrijving is ingevuld. De sleutelstructuur moet
| exact gelijk zijn aan lang/en/seo.php en lang/ar/seo.php.
|
| - pages.home            → de startpagina
| - pages.restaurant-info → pagina met openingstijden, allergenen en bezorging
| - pages.delivery-zones  → pagina met bezorggebieden
| - pages.fallback        → algemene terugvaltekst voor onbekende pagina's
*/

return [

    'pages' => [

        'home' => [
            'title' => 'Jemenitische Keuken Amsterdam | Mandi & traditionele gerechten bestellen',
            'description' => 'Authentieke Jemenitische keuken in Amsterdam. Bestel online of reserveer een tafel: mandi, haneeth en meer. Bezorging, afhalen of dine-in.',
        ],

        'restaurant-info' => [
            'title' => 'Restaurantinformatie | Openingstijden, allergenen en bezorging',
            'description' => 'Bekijk de openingstijden, allergeneninformatie en bezorggebieden van de Jemenitische Keuken in Amsterdam in één overzicht.',
        ],

        'delivery-zones' => [
            'title' => 'Bezorggebieden en bezorgkosten',
            'description' => 'Bekijk onze bezorggebieden, postcodes, bezorgkosten en het minimale bestelbedrag in Amsterdam.',
        ],

        'fallback' => [
            'title' => 'Jemenitische Keuken Amsterdam',
            'description' => 'Authentieke Jemenitische gerechten in Amsterdam. Bestel online of reserveer een tafel.',
        ],

    ],

];
