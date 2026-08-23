<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum ReservationStatus: string implements HasColor, HasLabel
{
    case PENDING = 'pending';
    case CONFIRMED = 'confirmed';
    case SEATED = 'seated';
    case CANCELLED = 'cancelled';
    case NO_SHOW = 'no_show';

    public function getLabel(): ?string
    {
        return __("messages.enums.reservation_status.{$this->value}");
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::PENDING => 'warning',
            self::CONFIRMED => 'success',
            self::SEATED => 'info',
            self::CANCELLED, self::NO_SHOW => 'danger',
        };
    }
}
