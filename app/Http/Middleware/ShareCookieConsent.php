<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

/**
 * ═══════════════════════════════════════════════════════════════════════════
 * 📄 المسار: app/Http/Middleware/ShareCookieConsent.php
 * ═══════════════════════════════════════════════════════════════════════════
 *
 * 🎯 الغرض:
 *    يقرأ قرار الموافقة على الكوكيز من كوكي الطرف الأول (المُشفَّرة)
 *    ويشاركه مع كل الواجهات عبر المتغير $cookieConsent،
 *    فلا تعتمد الواجهة على localStorage إطلاقاً.
 *
 * 🕒 آخر تحديث: 2026-11-10
 * ═══════════════════════════════════════════════════════════════════════════
 */
class ShareCookieConsent
{
    public function handle(Request $request, Closure $next): Response
    {
        View::share('cookieConsent', $this->readConsent($request));

        return $next($request);
    }

    /**
     * فكّ الكوكي والتحقق من صحة نسخة السياسة، وإرجاع حالة موحّدة.
     *
     * @return array{decided: bool, necessary: bool, analytics: bool, marketing: bool, timestamp: int|null}
     */
    private function readConsent(Request $request): array
    {
        $default = [
            'decided' => false,
            'necessary' => true,
            'analytics' => false,
            'marketing' => false,
            'timestamp' => null,
        ];

        $raw = $request->cookie(config('cookies.cookie_name'));

        if (! is_string($raw) || $raw === '') {
            return $default;
        }

        $data = json_decode($raw, true);

        // رفض أي قرار بنسخة قديمة من السياسة → إعادة طلب الموافقة.
        if (! is_array($data) || ($data['v'] ?? null) !== config('cookies.version')) {
            return $default;
        }

        return [
            'decided' => true,
            'necessary' => true,
            'analytics' => (bool) ($data['analytics'] ?? false),
            'marketing' => (bool) ($data['marketing'] ?? false),
            'timestamp' => isset($data['ts']) ? (int) $data['ts'] : null,
        ];
    }
}
