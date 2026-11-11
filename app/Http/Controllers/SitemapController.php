<?php

declare(strict_types=1);

/**
 * ═══════════════════════════════════════════════════════════════════════════
 * 📄 المسار: app/Http/Controllers/SitemapController.php
 * ═══════════════════════════════════════════════════════════════════════════
 *
 * 🎯 الغرض:
 *    توليد خريطة الموقع sitemap.xml لكل صفحات الموقع بكل اللغات المدعومة،
 *    مع روابط hreflang البديلة داخل كل عنوان ليفهم محرك البحث النسخ المتوازية.
 *
 * 🧩 يعتمد على:
 *    - App\Support\SeoMetadata::urlFor() (مصدر واحد لبناء الروابط)
 *    - config/locales.php (اللغات المدعومة والافتراضية)
 *
 * ⚠️ لا يتم إدراج lastmod لأننا لا نملك تاريخ تعديل حقيقي للمحتوى (تجنّب بيانات وهمية).
 * ═══════════════════════════════════════════════════════════════════════════
 */

namespace App\Http\Controllers;

use App\Models\RestaurantSetting;
use App\Support\SeoMetadata;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * أولوية كل صفحة داخل خريطة الموقع (الأعلى = الأهم).
     * ملاحظة: /delivery-zones لم تعد مُدرجة لأنها حوّلناها (301) إلى /restaurant-info.
     *
     * @var array<string, string>
     */
    private const PAGE_PRIORITIES = [
        'home' => '1.0',
        'restaurant-info' => '0.8',
    ];

    public function __invoke(): Response
    {
        $seo = new SeoMetadata(RestaurantSetting::current());

        $locales = $seo->locales();
        $defaultLocale = $seo->defaultLocale();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">'."\n";

        foreach (array_keys(self::PAGE_PRIORITIES) as $page) {
            foreach ($locales as $locale) {
                $xml .= "  <url>\n";
                $xml .= '    <loc>'.e($seo->urlFor($page, $locale))."</loc>\n";

                // روابط اللغات البديلة (hreflang) + x-default لكل عنوان.
                foreach ($locales as $alternate) {
                    $xml .= '    <xhtml:link rel="alternate" hreflang="'.$alternate.'" href="'
                        .e($seo->urlFor($page, $alternate))."\"/>\n";
                }
                $xml .= '    <xhtml:link rel="alternate" hreflang="x-default" href="'
                    .e($seo->urlFor($page, $defaultLocale))."\"/>\n";

                $xml .= '    <changefreq>'.($page === 'home' ? 'weekly' : 'monthly')."</changefreq>\n";
                $xml .= '    <priority>'.self::PAGE_PRIORITIES[$page]."</priority>\n";
                $xml .= "  </url>\n";
            }
        }

        $xml .= "</urlset>\n";

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }
}
