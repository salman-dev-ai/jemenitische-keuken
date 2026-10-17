<?php

declare(strict_types=1);

/**
 * ═══════════════════════════════════════════════════════════════════════════
 * 📄 Pad: lang/nl/coupons.php
 * ═══════════════════════════════════════════════════════════════════════════
 *
 * 🎯 Doel:
 *    Kortingsfoutvertalingen — gebruikt in CouponException en OrderService.
 *    Elke sleutel verwijst naar een gebruikersgerichte foutmelding.
 *
 * 🧩 Afhankelijk van:
 *    - App\Exceptions\CouponException
 *    - App\Services\OrderService
 *
 * 🕒 Laatst bijgewerkt: 2026-10-15
 * ═══════════════════════════════════════════════════════════════════════════
 */

return [
    'errors' => [
        // ─── CouponException ───
        'not_found' => 'Kortingscode ":code" niet gevonden.',
        'inactive' => 'Deze kortingscode is inactief.',
        'expired' => 'Deze kortingscode is verlopen.',
        'exhausted' => 'Deze kortingscode heeft het maximale aantal gebruiken bereikt.',
        'customer_limit' => 'U heeft het maximaal toegestane aantal (:max) gebruiken voor deze kortingscode bereikt.',
        'min_total' => 'Het besteltotaal moet minimaal €:min_total zijn.',
        'not_applicable' => 'Deze kortingscode is niet van toepassing op enig item in uw winkelwagen.',
        'already_claimed' => 'U heeft deze kortingscode al geclaimd.',
        'already_used' => 'Deze kortingscode is al gebruikt.',

        // ─── OrderService ───
        'invalid_code' => 'Ongeldige kortingscode.',
        'customer_required' => 'U moet ingelogd zijn om een kortingscode toe te passen.',
        'customer_not_found' => 'Klantaccount niet gevonden.',
    ],
];
