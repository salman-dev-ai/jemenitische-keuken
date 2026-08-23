<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum OrderType: string implements HasLabel
{
    case PICKUP = 'pickup';
    case DINE_IN = 'dine_in';

    public function getLabel(): ?string
    {
        return __("messages.enums.order_type.{$this->value}");
    }
}
