<?php

declare(strict_types=1);

/**
 * ═══════════════════════════════════════════════════════════════════════════
 * 📄 المسار: app/Support/SeoMetadata.php
 * ═══════════════════════════════════════════════════════════════════════════
 *
 * 🎯 الغرض:
 *    مولّد بيانات السيو (SEO) لصفحة واحدة. يجمع في مكان واحد:
 *      - عنوان الصفحة ووصفها (مع أولوية لتجاوزات لوحة التحكم).
 *      - الرابط الأساسي (canonical) وبدائل اللغات (hreflang).
 *      - صورة المشاركة الاجتماعية وقيم Open Graph.
 *      - البيانات المنظّمة JSON-LD (Restaurant / LocalBusiness).
 *
 * 🧩 يعتمد على:
 *    - config/locales.php  (اللغات المدعومة واللغة الافتراضية)
 *    - lang/{locale}/seo.php (النصوص الاحتياطية لكل صفحة)
 *    - RestaurantSetting   (بيانات المطعم القابلة للإدارة من Filament)
 *
 * 🔐 الأمان:
 *    - لا يقرأ أي مدخل من المستخدم؛ كل القيم تأتي من الإعدادات أو ملفات النصوص.
 *
 * ⚠️ تحذيرات مهمة:
 *    - لا يُضاف أي تقييم (aggregateRating) أو سعر وهمي؛ تُعرض فقط البيانات
 *      الحقيقية الموجودة في إعدادات المطعم.
 * ═══════════════════════════════════════════════════════════════════════════
 */

namespace App\Support;

use App\Models\RestaurantSetting;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Route;

final class SeoMetadata
{
    /**
     * أسماء الصفحات المدعومة في ملفات الترجمات seo.php.
     * (delivery-zones لم تعد ضمنها لأنها تُحوَّل 301 إلى restaurant-info)
     */
    private const PAGES = ['home', 'restaurant-info'];

    /** أيام الأسبوع بصيغة Schema.org */
    private const SCHEMA_DAYS = [
        'monday' => 'Monday',
        'tuesday' => 'Tuesday',
        'wednesday' => 'Wednesday',
        'thursday' => 'Thursday',
        'friday' => 'Friday',
        'saturday' => 'Saturday',
        'sunday' => 'Sunday',
    ];

    public function __construct(private readonly RestaurantSetting $settings) {}

    // ─────────────────────────────────────────────────────────────
    // 🌍 اللغة
    // ─────────────────────────────────────────────────────────────

    /**
     * اللغات المدعومة (من ملف الإعدادات).
     *
     * @return array<int, string>
     */
    public function locales(): array
    {
        return array_values((array) config('locales.supported', ['nl', 'en', 'ar']));
    }

    /**
     * اللغة الافتراضية (التي يُعرض عليها المسار الجذر "/").
     */
    public function defaultLocale(): string
    {
        return (string) config('locales.default', 'nl');
    }

    /**
     * اللغة الحالية للطلب مع التحقق من أنها مدعومة.
     */
    public function locale(): string
    {
        $locale = app()->getLocale();

        return in_array($locale, $this->locales(), true) ? $locale : $this->defaultLocale();
    }

    // ─────────────────────────────────────────────────────────────
    // 🏷️ العناصر الأساسية
    // ─────────────────────────────────────────────────────────────

    /**
     * اسم المطعم المترجم حسب اللغة الحالية.
     */
    public function siteName(): string
    {
        return $this->settings->localized_name;
    }

    /**
     * مفتاح الصفحة الحالية (home / restaurant-info).
     */
    public function pageKey(): string
    {
        $name = (string) (request()->route()?->getName() ?? 'home');
        $key = str_contains($name, '.') ? (string) strstr($name, '.', true) : $name;

        return in_array($key, self::PAGES, true) ? $key : 'home';
    }

    /**
     * عنوان الصفحة <title>.
     * الأولوية: تجاوز لوحة التحكم → نص الصفحة من الترجمة → اسم المطعم.
     */
    public function title(): string
    {
        if ($this->pageKey() === 'home') {
            $custom = trim((string) $this->settings->getTranslation('meta_title', $this->locale(), false));

            if ($custom !== '') {
                return $custom;
            }
        }

        return $this->trans("seo.pages.{$this->pageKey()}.title")
            ?? $this->trans('seo.pages.fallback.title')
            ?? $this->siteName();
    }

    /**
     * وصف الصفحة <meta name="description">.
     */
    public function description(): string
    {
        if ($this->pageKey() === 'home') {
            $custom = trim((string) $this->settings->getTranslation('meta_description', $this->locale(), false));

            if ($custom !== '') {
                return $custom;
            }
        }

        return $this->trans("seo.pages.{$this->pageKey()}.description")
            ?? $this->trans('seo.pages.fallback.description')
            ?? '';
    }

    // ─────────────────────────────────────────────────────────────
    // 🔗 الروابط واللغات البديلة
    // ─────────────────────────────────────────────────────────────

    /**
     * الرابط الأساسي (canonical) للصفحة الحالية بلغتها الحالية.
     */
    public function canonical(): string
    {
        return $this->urlFor($this->pageKey(), $this->locale());
    }

    /**
     * بناء رابط مطلق لصفحة معيّنة بلغة محددة.
     */
    public function urlFor(string $page, string $locale): string
    {
        $name = in_array($page, self::PAGES, true) ? $page : 'home';

        if (Route::has($name)) {
            return route($name, ['locale' => $locale], absolute: true);
        }

        return url("/{$locale}");
    }

    /**
     * بدائل اللغات لوسوم hreflang، وتشمل x-default.
     *
     * @return array<string, string>
     */
    public function alternates(): array
    {
        $alternates = [];

        foreach ($this->locales() as $locale) {
            $alternates[$locale] = $this->urlFor($this->pageKey(), $locale);
        }

        // x-default: النسخة المعروضة لمن لا تناسبه أي لغة محددة
        $alternates['x-default'] = $alternates[$this->defaultLocale()] ?? reset($alternates);

        return $alternates;
    }

    // ─────────────────────────────────────────────────────────────
    // 🖼️ المشاركة الاجتماعية (Open Graph / Twitter)
    // ─────────────────────────────────────────────────────────────

    /**
     * صورة المشاركة: صورة مخصّصة من لوحة التحكم وإلا صورة عامة من أصول الموقع.
     */
    public function ogImage(): string
    {
        $custom = trim((string) $this->settings->og_image);

        if ($custom === '') {
            return asset('images/home/home_logo.webp');
        }

        return str_starts_with($custom, 'http') ? $custom : asset(ltrim($custom, '/'));
    }

    /**
     * نوع Open Graph: صفحة المطعم أم موقع عام.
     */
    public function ogType(): string
    {
        return $this->pageKey() === 'home' ? 'restaurant' : 'website';
    }

    /**
     * قيمة og:locale للغة الحالية.
     */
    public function ogLocale(): string
    {
        return (string) (config("locales.og_locale.{$this->locale()}") ?? $this->locale());
    }

    /**
     * قيم og:locale:alternate لبقية اللغات.
     *
     * @return array<int, string>
     */
    public function alternateOgLocales(): array
    {
        $others = array_values(array_diff($this->locales(), [$this->locale()]));

        return array_values(array_filter(array_map(
            static fn (string $locale): string => (string) config("locales.og_locale.{$locale}"),
            $others,
        )));
    }

    /**
     * روابط حسابات التواصل الاجتماعي (تُستخدم في sameAs و twitter:site).
     *
     * @return array<string, string>
     */
    public function socialLinks(): array
    {
        $links = $this->settings->social_links;

        if (! is_array($links)) {
            return [];
        }

        return array_filter(array_map(
            static fn (mixed $url): string => is_string($url) ? trim($url) : '',
            $links,
        ));
    }

    /**
     * معرّف حساب X/Twitter (إن وُجد) لوسم twitter:site.
     */
    public function twitterHandle(): ?string
    {
        $links = $this->socialLinks();
        $url = $links['x'] ?? $links['twitter'] ?? null;

        if (! is_string($url) || $url === '') {
            return null;
        }

        $handle = trim((string) parse_url($url, PHP_URL_PATH), '/');

        return $handle !== '' ? '@'.ltrim($handle, '@') : null;
    }

    // ─────────────────────────────────────────────────────────────
    // 🧬 البيانات المنظّمة (JSON-LD)
    // ─────────────────────────────────────────────────────────────

    /**
     * بنية Restaurant/LocalBusiness الكاملة للصفحة.
     *
     * @return array<string, mixed>
     */
    public function schema(): array
    {
        $locale = $this->locale();
        $homeUrl = $this->urlFor('home', $locale);

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Restaurant',
            '@id' => $homeUrl.'#restaurant',
            'name' => $this->siteName(),
            'url' => $homeUrl,
            'inLanguage' => $locale,
            'image' => [$this->ogImage()],
            'telephone' => trim((string) $this->settings->phone),
            'email' => trim((string) $this->settings->email),
            'address' => $this->postalAddress($locale),
            'hasMenu' => $homeUrl.'#menu',
            'acceptsReservations' => (bool) $this->settings->accepts_reservations,
        ];

        // حذف الحقول النصية الفارغة حتى لا تُرسل قيم بلا معنى لجوجل
        $schema = array_filter(
            $schema,
            static fn (mixed $value): bool => $value !== '' && $value !== null,
        );

        $cuisines = array_values(array_filter(array_map(
            static fn (mixed $item): string => is_string($item) ? trim($item) : '',
            (array) $this->settings->serves_cuisine,
        )));

        if ($cuisines !== []) {
            $schema['servesCuisine'] = $cuisines;
        }

        $priceRange = trim((string) $this->settings->price_range);

        if ($priceRange !== '') {
            $schema['priceRange'] = $priceRange;
        }

        $specification = $this->openingHoursSpecification();

        if ($specification !== []) {
            $schema['openingHoursSpecification'] = $specification;
        }

        $links = array_values($this->socialLinks());

        if ($links !== []) {
            $schema['sameAs'] = $links;
        }

        $mapsLink = trim((string) $this->settings->google_maps_link);

        if ($mapsLink !== '') {
            $schema['hasMap'] = $mapsLink;
        }

        if ($this->settings->latitude !== null && $this->settings->longitude !== null) {
            $schema['geo'] = [
                '@type' => 'GeoCoordinates',
                'latitude' => $this->settings->latitude,
                'longitude' => $this->settings->longitude,
            ];
        }

        return $schema;
    }

    /**
     * العنوان البريدي بصيغة Schema.org.
     *
     * @return array<string, string>
     */
    private function postalAddress(string $locale): array
    {
        $street = trim((string) $this->settings->getTranslation('address', $locale, false));

        if ($street === '') {
            $street = trim((string) $this->settings->address);
        }

        $address = [
            '@type' => 'PostalAddress',
            'streetAddress' => $street,
            'addressLocality' => trim((string) $this->settings->city),
            'postalCode' => trim((string) $this->settings->postal_code),
            'addressCountry' => 'NL',
        ];

        return array_filter($address, static fn (string $value): bool => $value !== '');
    }

    /**
     * أوقات العمل بصيغة OpeningHoursSpecification (24 ساعة).
     *
     * @return array<int, array<string, string>>
     */
    public function openingHoursSpecification(): array
    {
        $rows = $this->settings->opening_hours;

        if (! is_array($rows)) {
            return [];
        }

        $specification = [];

        foreach ($rows as $row) {
            if (! is_array($row)) {
                continue;
            }

            $day = strtolower((string) ($row['day'] ?? ''));

            if (! isset(self::SCHEMA_DAYS[$day]) || (bool) ($row['is_closed'] ?? false)) {
                continue;
            }

            // الصيغة الحالية: وقت يدوي بنظام 12 ساعة + صباحاً/مساءً
            $opens = $this->to24Hours(
                (string) ($row['opens_time'] ?? ($row['opens_at'] ?? '')),
                (string) ($row['opens_period'] ?? ''),
            );

            $closes = $this->to24Hours(
                (string) ($row['closes_time'] ?? ($row['closes_at'] ?? '')),
                (string) ($row['closes_period'] ?? ''),
            );

            if ($opens === null || $closes === null) {
                continue;
            }

            $specification[] = [
                '@type' => 'OpeningHoursSpecification',
                'dayOfWeek' => 'https://schema.org/'.self::SCHEMA_DAYS[$day],
                'opens' => $opens,
                'closes' => $closes,
            ];
        }

        return $specification;
    }

    /**
     * تحويل الوقت إلى صيغة 24 ساعة (HH:MM) المطلوبة في Schema.org.
     * يدعم الصيغتين: 12 ساعة مع صباحاً/مساءً، و24 ساعة مباشرة.
     */
    private function to24Hours(string $time, string $period): ?string
    {
        if (! preg_match('/^(\d{1,2}):(\d{2})$/', trim($time), $matches)) {
            return null;
        }

        $hours = (int) $matches[1];
        $minutes = $matches[2];

        // إذا كان الوقت أصلًا بنظام 24 ساعة (بدون فترة) نعيده كما هو
        if ($period === '') {
            return $hours <= 23 ? sprintf('%02d:%s', $hours, $minutes) : null;
        }

        if ($hours < 1 || $hours > 12) {
            return null;
        }

        $hours %= 12;

        if (strtolower($period) === 'pm') {
            $hours += 12;
        }

        return sprintf('%02d:%s', $hours, $minutes);
    }

    // ─────────────────────────────────────────────────────────────
    // 🛠️ أدوات داخلية
    // ─────────────────────────────────────────────────────────────

    /**
     * قراءة نص ترجمة، مع إرجاع null إذا كان المفتاح غير موجود.
     */
    private function trans(string $key): ?string
    {
        if (! Lang::has($key)) {
            return null;
        }

        $value = trim((string) __($key));

        return $value === '' ? null : $value;
    }
}
