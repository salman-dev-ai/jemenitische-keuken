<?php

declare(strict_types=1);

/**
 * 📄 المسار: app/Support/OpeningHoursPresenter.php
 *
 * 🎯 يحوّل opening_hours (الفورم: وقت يدوي + صباحاً/مساءً) إلى صفوف جاهزة للعرض،
 *    صف واحد لكل يوم. يدعم أيضاً الصيغة السابقة (opens_at / closes_at بنظام 24 ساعة).
 */

namespace App\Support;

use Carbon\Carbon;

class OpeningHoursPresenter
{
    /** مفتاح اليوم => رقم اليوم في JavaScript (getDay: الأحد = 0) */
    private const JS_DAY = [
        'sunday'    => 0,
        'monday'    => 1,
        'tuesday'   => 2,
        'wednesday' => 3,
        'thursday'  => 4,
        'friday'    => 5,
        'saturday'  => 6,
    ];

    /**
     * @param  array<int, array<string, mixed>>|null  $openingHours
     * @return array<int, array{label: string, js: array<int, int>, hours: string|null}>
     */
    public static function rows(?array $openingHours): array
    {
        if (empty($openingHours)) {
            return [];
        }

        $byDay = collect($openingHours)->keyBy('day');
        $rows  = [];

        foreach (self::JS_DAY as $day => $jsIndex) {
            $item = $byDay->get($day);

            if ($item === null) {
                continue;
            }

            $rows[] = [
                'label' => Carbon::parse("this {$day}")
                    ->locale(app()->getLocale())
                    ->isoFormat('dddd'),
                'js'    => [$jsIndex],
                'hours' => self::formatHours($item),
            ];
        }

        // الترتيب: الإثنين → الأحد
        usort($rows, fn (array $a, array $b): int => (($a['js'][0] + 6) % 7) <=> (($b['js'][0] + 6) % 7));

        return $rows;
    }

    /**
     * يُرجع نص الساعات (مثل "9:30 ص - 11:00 م") أو null إذا كان اليوم مغلقاً.
     *
     * @param  array<string, mixed>  $item
     */
    private static function formatHours(array $item): ?string
    {
        if ((bool) ($item['is_closed'] ?? false)) {
            return null;
        }

        // الصيغة الجديدة: وقت يدوي + صباحاً/مساءً
        if (! empty($item['opens_time']) && ! empty($item['closes_time'])) {
            $open  = self::formatTime((string) $item['opens_time'], (string) ($item['opens_period'] ?? 'am'));
            $close = self::formatTime((string) $item['closes_time'], (string) ($item['closes_period'] ?? 'pm'));

            return "{$open} - {$close}";
        }

        // الصيغة السابقة (24 ساعة)
        if (! empty($item['opens_at']) && ! empty($item['closes_at'])) {
            return "{$item['opens_at']} - {$item['closes_at']}";
        }

        return null;
    }

    private static function formatTime(string $time, string $period): string
    {
        $labels = app()->getLocale() === 'ar'
            ? ['am' => 'ص', 'pm' => 'م']
            : ['am' => 'AM', 'pm' => 'PM'];

        return ltrim($time, '0') . ' ' . ($labels[$period] ?? '');
    }
}
