<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum OrderType: string implements HasColor, HasLabel
{
    case PICKUP = 'pickup';
    case DINE_IN = 'dine_in';

    public function getLabel(): ?string
    {
        return __("messages.enums.order_type.{$this->value}");
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::PICKUP => 'info',
            self::DINE_IN => 'success',
        };
    }
}
