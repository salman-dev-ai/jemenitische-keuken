<?php

declare(strict_types=1);

/**
 * مكوّن ليفواير للتذييل: يجلب إعدادات المطعم ويجهز الاسم والعنوان ووسائل التواصل
 * وساعات العمل المجمّعة، مع قيم احتياطية من ملفات الترجمة وتبديل اللغة.
 */

namespace App\Livewire;

use App\Models\RestaurantSetting;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Lazy;
use Livewire\Component;

// #[Lazy]
final class Footer extends Component
{
    /** ترتيب أيام الأسبوع المعتمد في التجميع. */
    private const DAY_ORDER = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

    /** مطابقة اليوم مع ترقيم جافاسكربت لتمييز اليوم الحالي. */
    private const JS_DAY = [
        'sunday' => 0, 'monday' => 1, 'tuesday' => 2, 'wednesday' => 3,
        'thursday' => 4, 'friday' => 5, 'saturday' => 6,
    ];

    private const RTL_LOCALES = ['ar', 'he', 'fa', 'ur'];

    /** هيكل بديل يظهر أثناء التحميل الكسول. */
    public function placeholder(): string
    {
        return '<footer class="min-h-[640px] animate-pulse bg-[#e9c9a6]" aria-hidden="true"></footer>';
    }

    /** حفظ اللغة المختارة في الجلسة ثم إعادة تحميل الصفحة الحالية. */
    public function setLocale(string $locale): void
    {
        $supported = config('app.supported_locales', ['ar', 'en', 'nl']);

        if (! in_array($locale, $supported, true)) {
            return;
        }

        session()->put('locale', $locale);
        $this->redirect(url()->previous(), navigate: true);
    }

    public function render(): View
    {
        $locale = app()->getLocale();

        return view('livewire.footer', [
            'f' => $this->footerData(),
            'locale' => $locale,
            'dir' => in_array($locale, self::RTL_LOCALES, true) ? 'rtl' : 'ltr',
        ]);
    }

    /** تجميع كل بيانات التذييل من الإعدادات مع القيم الاحتياطية. */
    private function footerData(): array
    {
        $s = RestaurantSetting::query()->first();

        $address = trim((string) $s?->getAttribute('address'));
        $city = trim((string) $s?->getAttribute('city'));
        $postal = trim((string) $s?->getAttribute('postal_code'));
        $cityLine = implode(', ', array_filter([$city, $postal]));

        $phone = trim((string) $s?->getAttribute('phone'));
        $whatsappDigits = preg_replace('/\D+/', '', (string) $s?->getAttribute('whatsapp'));
        $tiktok = trim((string) $s?->getAttribute('tiktok_url'));

        $mapsLink = $s?->getAttribute('google_maps_url')
            ?: ($address !== ''
                ? 'https://www.google.com/maps/search/?api=1&query='
                    . rawurlencode(trim("{$address} {$cityLine}"))
                : null);

        return [
            'name' => $s?->localized_name ?: __('footer.default_name'),
            'name_sub' => $s?->getAttribute('name_sub') ?: __('footer.brand_sub'),
            'logo' => $this->mediaUrl($s?->getAttribute('logo'), 'images/footer/logo.png'),
            'background' => $this->mediaUrl($s?->getAttribute('footer_background'), 'images/footer/yemen-bg.jpg'),
            'hero_image' => $this->mediaUrl($s?->getAttribute('featured_dish_image'), 'images/footer/mandi.jpg'),
            'featured_title' => $s?->getAttribute('featured_dish_title') ?: __('footer.featured_title'),
            'featured_text' => $s?->getAttribute('featured_dish_description') ?: __('footer.featured_text'),
            'accepts_reservations' => (bool) $s?->getAttribute('accepts_reservations'),

            'address' => $address,
            'city_line' => $cityLine,
            'maps_link' => $mapsLink,

            'phone' => $phone,
            'phone_href' => preg_replace('/[^\d+]/', '', $phone),
            'email' => trim((string) $s?->getAttribute('email')),

            'tiktok' => $tiktok !== '' ? $tiktok : null,
            'whatsapp_href' => $whatsappDigits !== '' ? 'https://wa.me/' . $whatsappDigits : null,

            'hours' => $this->groupHours($s?->getAttribute('opening_hours')),
            'locales' => config('app.supported_locales', ['ar', 'en', 'nl']),
        ];
    }

    /** دمج الأيام المتتالية ذات الساعات المتطابقة في صف واحد. */
    private function groupHours(mixed $hours): array
    {
        if (! is_array($hours) || $hours === []) {
            return [];
        }

        $hours = array_change_key_case($hours, CASE_LOWER);
        $groups = [];

        foreach (self::DAY_ORDER as $day) {
            $text = $this->formatSlot($hours[$day] ?? null);
            $last = array_key_last($groups);

            if ($last !== null && $groups[$last]['hours'] === $text) {
                $groups[$last]['days'][] = $day;
            } else {
                $groups[] = ['days' => [$day], 'hours' => $text];
            }
        }

        return array_map(function (array $group): array {
            $days = $group['days'];
            $first = __('footer.days.' . $days[0]);
            $end = __('footer.days.' . $days[array_key_last($days)]);

            return [
                'label' => count($days) > 1 ? "{$first} – {$end}" : $first,
                'hours' => $group['hours'],
                'js' => array_map(static fn (string $d): int => self::JS_DAY[$d], $days),
            ];
        }, $groups);
    }

    /** تحويل صيغ الساعات المختلفة إلى نص واحد أو null عند الإغلاق. */
    private function formatSlot(mixed $raw): ?string
    {
        if ($raw === null || $raw === false || $raw === '') {
            return null;
        }

        if (is_string($raw)) {
            $value = trim($raw);

            return in_array(mb_strtolower($value), ['closed', 'مغلق', '-', 'x'], true) ? null : $value;
        }

        if (! is_array($raw) || ! empty($raw['closed'])) {
            return null;
        }

        if (isset($raw['open'], $raw['close'])) {
            return $raw['open'] . ' – ' . $raw['close'];
        }

        if (array_filter($raw, 'is_scalar') === $raw) {
            return implode(' – ', array_filter(array_map('strval', $raw))) ?: null;
        }

        $parts = [];
        foreach ($raw as $slot) {
            $text = is_array($slot)
                ? implode(' – ', array_filter(array_map('strval', $slot)))
                : trim((string) $slot);

            if ($text !== '') {
                $parts[] = $text;
            }
        }

        return $parts ? implode(' / ', $parts) : null;
    }

    /** تحويل مسار الملف المخزن إلى رابط، أو استخدام الصورة الاحتياطية. */
    private function mediaUrl(?string $path, string $fallback): string
    {
        if (blank($path)) {
            return asset($fallback);
        }

        return str_starts_with($path, 'http') ? $path : Storage::disk('public')->url($path);
    }
}
