<?php

use App\Http\Controllers\CookieConsentController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\PaymentReturnController;
use App\Http\Controllers\RobotsController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\Webhooks\PayProWebhookController;
use App\Models\RestaurantSetting;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| 🌍 بنية الروابط متعددة اللغات (لأغراض السيو)
|--------------------------------------------------------------------------
| لكل لغة رابط فرعي خاص بها (/nl /en /ar) حتى تفهرس محركات البحث نسخة
| مستقلة لكل لغة، وتُربط معاً بوسوم hreflang. القائمة الافتراضية للغات
| معرّفة في config/locales.php (المصدر الوحيد للحقيقة).
|
| ⚠️ مهم: الجذر "/" يُحوَّل (301) إلى اللغة الافتراضية لمنع المحتوى المكرر.
| كل استدعاء route('home') داخل العرض يولّد الرابط بلغة الطلب تلقائياً لأن
| middleware SetLocale يضبط URL::defaults(['locale' => ...]).
*/

// ─────────────────────────────────────────────────────────────
// 1) ملفات محركات البحث: خريطة الموقع و robots
// ─────────────────────────────────────────────────────────────
Route::get('sitemap.xml', SitemapController::class)->name('sitemap');
Route::get('robots.txt', RobotsController::class)->name('robots');

// ─────────────────────────────────────────────────────────────
// 2) الجذر: تحويل دائم (301) إلى اللغة الافتراضية
// ─────────────────────────────────────────────────────────────
Route::redirect('/', '/'.config('locales.default'), 301)->name('home.redirect');

// ─────────────────────────────────────────────────────────────
// 3) صفحات الموقع ضمن بادئة اللغة (locale)
// ─────────────────────────────────────────────────────────────
Route::prefix('{locale}')
    ->whereIn('locale', config('locales.supported'))
    ->group(function (): void {

        Route::get('/', function () {
            return view('welcome', ['settings' => RestaurantSetting::current()]);
        })->name('home');

        // صفحة مناطق التوصيل القديمة: دُمج محتواها في صفحة معلومات المطعم،
        // لذا نحوّلها تحويلاً دائماً (301) نحو /restaurant-info للحفاظ على أي
        // روابط قديمة (Backlinks) قبل حذف المسار، وتبقى محصّنة سيوياً.
        Route::get('delivery-zones', function (string $locale) {
            return redirect()->route('restaurant-info', ['locale' => $locale], 301);
        })->name('delivery-zones');

        // صفحة معلومات المطعم الشاملة: أوقات الدوام + مسببات الحساسية + جدول التوصيل.
        Route::get('restaurant-info', function () {
            return view('restaurant-info', ['settings' => RestaurantSetting::current()]);
        })->name('restaurant-info');

    });

// ─────────────────────────────────────────────────────────────
// 4) تبديل اللغة: يخزّن الاختيار بالجلسة ثم يعيد الزائر لنفس الصفحة
//    (يبقى للتوافق؛ الروابط المباشرة بلغة أخرى أفضل سيوياً)
// ─────────────────────────────────────────────────────────────
Route::get('lang/switch/{locale}', [LanguageController::class, 'switch'])
    ->whereIn('locale', config('locales.supported'))
    ->name('lang.switch');

// ─────────────────────────────────────────────────────────────
// 5) حفظ/تعديل قرار الموافقة على الكوكيز (GDPR) — محمي بحدّ لتفادي إساءة الاستخدام.
// ─────────────────────────────────────────────────────────────
Route::post('cookie-consent', [CookieConsentController::class, 'store'])
    ->middleware('throttle:30,1')
    ->name('cookie-consent.store');

// ─────────────────────────────────────────────────────────────
// 6) Webhooks: PayPro (Payment)
// ─────────────────────────────────────────────────────────────
Route::post('/webhooks/paypro', PayProWebhookController::class)
    ->name('webhooks.paypro')
    ->withoutMiddleware([ValidateCsrfToken::class]);

Route::get('payment/return/{order:order_number}', PaymentReturnController::class)
    ->middleware('signed')
    ->name('payment.return');
