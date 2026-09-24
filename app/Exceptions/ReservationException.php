<?php

declare(strict_types=1);

/**
 * ═══════════════════════════════════════════════════════════════════════════
 * 📄 المسار: app/Exceptions/ReservationException.php
 * ═══════════════════════════════════════════════════════════════════════════
 *
 * 🎯 الغرض:
 *    استثناءات مخصصة لأخطاء الحجوزات.
 *
 * ⚠️ تحذيرات مهمة:
 *    - لا يوجد timeConflict (المطعم يستقبل كل الحجوزات).
 *
 * 🕒 آخر تحديث: 2026-09-24
 * ═══════════════════════════════════════════════════════════════════════════
 */

namespace App\Exceptions;

use Exception;

class ReservationException extends Exception
{
    /**
     * 👤 بيانات العميل مفقودة.
     */
    public static function missingCustomerData(): self
    {
        return new self(__('messages.errors.missing_customer_data'));
    }

    /**
     * 📋 حقول أساسية مفقودة.
     */
    public static function missingRequiredFields(): self
    {
        return new self(__('messages.errors.missing_required_fields'));
    }

    /**
     * 👥 عدد أشخاص غير صالح.
     */
    public static function invalidPartySize(): self
    {
        return new self(__('messages.errors.invalid_party_size'));
    }

    /**
     * 📅 تاريخ في الماضي.
     */
    public static function pastDate(): self
    {
        return new self(__('messages.errors.past_date'));
    }

    /**
     * 🔄 حالة حجز غير صالحة.
     */
    public static function invalidStatus(): self
    {
        return new self(__('messages.errors.invalid_status'));
    }

    /**
     * 📆 صيغة تاريخ خاطئة.
     */
    public static function invalidDateFormat(): self
    {
        return new self(__('messages.errors.invalid_date_format'));
    }

    /**
     * 🕐 صيغة وقت خاطئة.
     */
    public static function invalidTimeFormat(): self
    {
        return new self(__('messages.errors.invalid_time_format'));
    }
}