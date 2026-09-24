<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class ReservationException extends Exception
{
    public static function missingCustomerData(): self
    {
        return new self(__('messages.errors.missing_customer_data'));
    }

    public static function missingRequiredFields(): self
    {
        return new self(__('messages.errors.missing_required_fields'));
    }

    public static function invalidPartySize(): self
    {
        return new self(__('messages.errors.invalid_party_size'));
    }

    public static function pastDate(): self
    {
        return new self(__('messages.errors.past_date'));
    }

    public static function timeConflict(): self
    {
        return new self(__('messages.errors.time_conflict'));
    }

    public static function invalidStatus(): self
    {
        return new self(__('messages.errors.invalid_status'));
    }

    public static function invalidDateFormat(): self
    {
        return new self(__('messages.errors.invalid_date_format'));
    }

    public static function invalidTimeFormat(): self
    {
        return new self(__('messages.errors.invalid_time_format'));
    }
}