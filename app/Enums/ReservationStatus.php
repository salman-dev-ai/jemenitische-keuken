<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

/**
 * Responsibility: Defines strict states for table reservations with UI integration for Filament.
 */
    enum ReservationStatus: string implements HasLabel, HasColor
{
    // كلمة مفتاحية محجوزة في لغة PHP، تخبر المترجم: "أنا الآن سأقوم بتعريف حالة جديدة وثابتة من حالات الحجز
    case PENDING = 'pending';
    case CONFIRMED = 'confirmed';
    case SEATED = 'seated';
    case CANCELLED = 'cancelled';
    case NO_SHOW = 'no_show';

    public function getLabel(): ?string
    {
        return match ($this) {
            // __ use to translate
            self::PENDING => __('Pending'),
            self::CONFIRMED => __('Confirmed'),
            self::SEATED => __('Seated'),
            self::CANCELLED => __('Cancelled'),
            self::NO_SHOW => __('No Show'),
        };
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
