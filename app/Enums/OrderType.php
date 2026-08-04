<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

/**
 * Responsibility: Manages different types of orders (e.g., dine-in, pickup).
 */
enum OrderType: string implements HasLabel
{
    case PICKUP = 'pickup';
    case DINE_IN = 'dine_in';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::PICKUP => __('Pickup (Takeaway)'),
            self::DINE_IN => __('Dine-in'),
        };
    }
}
