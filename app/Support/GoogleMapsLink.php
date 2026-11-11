<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * يحوّل رابط خرائط جوجل الذي يدخله المدير (مثل https://maps.app.goo.gl/XXXX
 * أو رابط google.com/maps الكامل) إلى إحداثيات + رابط تضمين للخريطة.
 * الروابط المختصرة لا تعمل داخل iframe مباشرة، لذلك نفكّها من السيرفر مرة واحدة ونخزّن النتيجة.
 */
class GoogleMapsLink
{
    private const ALLOWED_HOSTS = [
        'maps.app.goo.gl',
        'goo.gl',
        'www.google.com',
        'google.com',
        'maps.google.com',
    ];

    /**
     * @return array{lat: float, lng: float, name: ?string, embed: string, url: string}|null
     */
    public static function resolve(?string $link): ?array
    {
        $link = trim((string) $link);

        if ($link === '' || ! self::isAllowed($link)) {
            return null;
        }

        return Cache::remember('gmaps-link:'.md5($link), now()->addDays(30), function () use ($link) {
            $final = $link;

            // الرابط المختصر: نتبع التحويل للحصول على الرابط الكامل
            if (! str_contains($link, '/maps/place') && ! str_contains($link, '@')) {
                try {
                    $effective = null;
                    Http::timeout(8)
                        ->withHeaders([
                            'User-Agent' => 'Mozilla/5.0 (compatible; MapLinkResolver/1.0)',
                            // تجاوز صفحة موافقة الكوكيز التي تظهر لسيرفرات أوروبا
                            'Cookie' => 'CONSENT=YES+cb; SOCS=CAI',
                        ])
                        ->withOptions([
                            'allow_redirects' => ['max' => 8],
                            'on_stats' => function ($stats) use (&$effective) {
                                $effective = (string) $stats->getEffectiveUri();
                            },
                        ])
                        ->get($link);

                    // إن حوّلنا جوجل لصفحة الموافقة، الرابط الحقيقي داخل المعامل continue
                    if ($effective && str_contains($effective, 'consent.google.')) {
                        parse_str((string) parse_url($effective, PHP_URL_QUERY), $q);
                        $effective = $q['continue'] ?? $effective;
                    }

                    if ($effective && self::isAllowed($effective)) {
                        $final = $effective;
                    }
                } catch (\Throwable $e) {
                    Log::warning('GoogleMapsLink: فشل فك الرابط', ['link' => $link, 'error' => $e->getMessage()]);

                    return null;
                }
            }

            // الإحداثيات الدقيقة للمكان (!3d lat !4d lng) ثم الاحتياطي (@lat,lng)
            if (preg_match('/!3d(-?\d+\.\d+)!4d(-?\d+\.\d+)/', $final, $m)
                || preg_match('/@(-?\d+\.\d+),(-?\d+\.\d+)/', $final, $m)
                || preg_match('/[?&](?:q|query|ll)=(-?\d+\.\d+),(-?\d+\.\d+)/', $final, $m)) {
                $lat = (float) $m[1];
                $lng = (float) $m[2];
            } else {
                Log::warning('GoogleMapsLink: لم أجد إحداثيات في الرابط', ['link' => $link, 'final' => $final]);

                return null;
            }

            $name = null;
            if (preg_match('#/maps/place/([^/@]+)#', $final, $n)) {
                $name = trim(urldecode(str_replace('+', ' ', $n[1])));
            }

            return [
                'lat' => $lat,
                'lng' => $lng,
                'name' => $name,
                'embed' => 'https://www.google.com/maps?q='.$lat.','.$lng.'&z=16&output=embed',
                'url' => $final,
            ];
        });
    }

    private static function isAllowed(string $url): bool
    {
        $parts = parse_url($url);
        $host = strtolower($parts['host'] ?? '');
        $scheme = strtolower($parts['scheme'] ?? '');

        return $scheme === 'https' && in_array($host, self::ALLOWED_HOSTS, true);
    }
}
