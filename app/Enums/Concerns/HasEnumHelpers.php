<?php

declare(strict_types=1);

/**
 * واجهة مساعدة مشتركة لجميع Enums التطبيق.
 * توفّر methods موحّدة (values / names / options) لتجنّب تكرار الكود.
 * تُستخدم من Filament Select / Validation Rules / Seeders.
 */

namespace App\Enums\Concerns;

trait HasEnumHelpers
{
    /**
     * إرجاع جميع القيم النصية — يُستخدم في validation rules و database queries.
     *
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * إرجاع جميع أسماء الحالات (case names) — يُستخدم في الفلترة المنطقية.
     *
     * @return array<int, string>
     */
    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }

    /**
     * إرجاع مصفوفة [value => label] جاهزة مباشرة لـ Filament Select.
     *
     * @return array<string, string>
     */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $case): array => [
                $case->value => (string) $case->getLabel(),
            ])
            ->all();
    }
}