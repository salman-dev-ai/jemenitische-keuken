<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\CookieConsent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * ═══════════════════════════════════════════════════════════════════════════
 * 📄 المسار: app/Http/Controllers/CookieConsentController.php
 * ═══════════════════════════════════════════════════════════════════════════
 *
 * 🎯 الغرض:
 *    حفظ قرار الموافقة بعد تحقق خادمي صارم، وإصدار كوكي الطرف الأول الآمنة،
 *    وتسجيل الموافقة في قاعدة البيانات (إثبات الموافقة — GDPR).
 *
 * 🕒 آخر تحديث: 2026-11-10
 * ═══════════════════════════════════════════════════════════════════════════
 */
class CookieConsentController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        // التحقق الخادمي الإلزامي: لا نثق بالواجهة إطلاقاً.
        $validated = $request->validate([
            'analytics' => ['required', 'boolean'],
            'marketing' => ['required', 'boolean'],
        ]);

        $analytics = (bool) $validated['analytics'];
        $marketing = (bool) $validated['marketing'];

        // نُعيد استخدام معرّف الزائر المجهول نفسه إن وُجد، وإلا نولّد معرّفاً جديداً.
        $existing = json_decode((string) $request->cookie(config('cookies.cookie_name')), true);
        $uid = is_array($existing) && ! empty($existing['uid'])
            ? (string) $existing['uid']
            : (string) Str::uuid();

        $payload = [
            'v' => config('cookies.version'),
            'necessary' => true,                 // دائماً مفعّلة
            'analytics' => $analytics,
            'marketing' => $marketing,
            'ts' => now()->timestamp,            // ختم زمني
            'uid' => $uid,                       // معرّف مجهول لربط السجل
        ];

        // ── تسجيل الموافقة (إثبات) — بصمة IP مجزأة بدل حفظه خاماً ──
        CookieConsent::updateOrCreate(
            ['uuid' => $uid],
            [
                'necessary' => true,
                'analytics' => $analytics,
                'marketing' => $marketing,
                'version' => (int) config('cookies.version'),
                'ip_hash' => $this->hashIp($request->ip()),
                'user_agent' => Str::limit((string) $request->userAgent(), 512, ''),
            ],
        );

        $minutes = (int) config('cookies.lifetime_days') * 24 * 60;

        // كوكي آمنة: Secure على HTTPS، HttpOnly (لا نحتاج قراءتها من JS)، SameSite=Lax.
        $cookie = cookie(
            name: config('cookies.cookie_name'),
            value: json_encode($payload, JSON_UNESCAPED_UNICODE),
            minutes: $minutes,
            path: '/',
            domain: null,
            secure: $request->isSecure(),
            httpOnly: true,
            raw: false,
            sameSite: 'lax',
        );

        return response()
            ->json(['ok' => true, 'consent' => $payload])
            ->withCookie($cookie);
    }

    /**
     * بصمة IP مجزأة (SHA-256) — لا نحفظ عنوان IP الخام التزاماً بتقليل البيانات.
     */
    private function hashIp(?string $ip): ?string
    {
        if ($ip === null || $ip === '') {
            return null;
        }

        return hash('sha256', $ip.config('app.key'));
    }
}
