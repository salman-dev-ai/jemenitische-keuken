<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

/**
 * Responsibility: Manages order status
 */
enum OrderStatus :string implements HasLabel,HasColor
{
    case PENDING = 'pending';
    case PROCESSING = 'processing';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';
public function getLabel(): ?string {
    return match($this){
        self:: PENDING=> __('Pending'),
        self:: PROCESSING=> __('Processing'),
        self:: COMPLETED=> __('Completed'),
        self:: CANCELLED=> __('Cancelled'),
    };  }


    public function getColor(): string|array|null
    {
        return match ($this) {
            self::PENDING => 'warning',
            self::PROCESSING => 'info',
            self::COMPLETED => 'success',
            self::CANCELLED => 'danger',
        };
    }
}
