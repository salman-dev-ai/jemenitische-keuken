<?php

declare(strict_types=1);

/**
 * ═══════════════════════════════════════════════════════════════════════════
 * 📄 المسار: app/Http/Middleware/SetLocale.php
 * ═══════════════════════════════════════════════════════════════════════════
 *
 * 🎯 الغرض:
 *    تحديد لغة الطلب اعتماداً على بادئة الرابط (/nl /en /ar) أولاً، لأن ذلك
 *    هو ما تعتمد عليه محركات البحث لتمييز نسخة كل لغة. ثم الجلسة، ثم لغة
 *    المتصفح، وأخيراً اللغة الافتراضية.
 *
 * 🔐 الأمان: لا يقرأ أي مدخل غير موثوق؛ يقتصر على قائمة اللغات المدعومة.
 *
 * ⚠️ مهم: يضبط URL::defaults(['locale' => ...]) كي يولّد أي استدعاء route('home')
 *    الرابط بلغة الطلب تلقائياً دون الحاجة لتعديل بقية ملفات العرض.
 * ═══════════════════════════════════════════════════════════════════════════
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var array<int, string> $supported */
        $supported = array_values((array) config('locales.supported', ['nl', 'en', 'ar']));
        $defaultLocale = (string) config('locales.default', 'nl');

        // 1) بادئة الرابط (locale) لها الأولوية القصوى — أساس الروابط الصديقة للسيو.
        $routeLocale = $request->route()?->parameter('locale');

        if (is_string($routeLocale) && in_array($routeLocale, $supported, true)) {
            $locale = $routeLocale;
        } elseif (is_string($sessionLocale = session('locale')) && in_array($sessionLocale, $supported, true)) {
            // 2) اختيار الزائر المحفوظ بالجلسة.
            $locale = $sessionLocale;
        } else {
            // 3) لغة المتصفح، وإلا اللغة الافتراضية.
            $locale = $request->getPreferredLanguage($supported) ?: $defaultLocale;
        }

        // حماية إضافية: أي قيمة غير مدعومة ترجع للافتراضية.
        if (! in_array($locale, $supported, true)) {
            $locale = $defaultLocale;
        }

        // نحفظ اللغة بالجلسة لتبقى ثابتة في الطلبات اللاحقة التي بلا بادئة (مثل Livewire).
        session()->put('locale', $locale);

        app()->setLocale($locale);
        app()->setFallbackLocale('en');

        // يجعل قيم اللغة الافتراضية متاحة لتوليد الروابط في كل الصفحات.
        URL::defaults(['locale' => $locale]);

        return $next($request);
    }
}
