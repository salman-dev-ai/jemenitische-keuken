<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\RestaurantSetting;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\FileViewFinder;
use InvalidArgumentException;

class AppServiceProvider extends ServiceProvider
{
    /**
     * تسجيل الخدمات في الـ Container.
     */
    public function register(): void
    {
        // ✅ RestaurantSetting كـ Singleton — يُحل مرة واحدة فقط
        //    السبب: PricingService (وغيره) يحقنه في constructor
        //    بدون هذا، Laravel يحقن كائن فارغ → vat_rate = null → tax = 0
        $this->app->singleton(
            RestaurantSetting::class,
            fn (): RestaurantSetting => RestaurantSetting::current(),
        );

        // ✅ تجاوز قيود open_basedir على ويندوز (IIS/Plesk):
        // Livewire يمرّر نص قالب الصفحة الكامل إلى Blade::render()، فيعتبره Laravel
        // اسم View ويستدعي file_exists() عليه. النص يحتوي رموزًا غير صالحة في أسماء
        // ملفات ويندوز (? < >)، فيفشل فحص open_basedir ويُطلق تحذيرًا يتحوّل إلى
        // ErrorException → خطأ 500. هذا الربط يرفض تلك الأسماء قبل لمس نظام الملفات؛
        // فيلتقط Laravel استثناء InvalidArgumentException ويعيد false، ثم يولّد ملفًا
        // مُصرّفًا بالـ hash كما هو مصمَّم أصلًا في BladeCompiler::render().
        $this->app->bind('view.finder', function ($app): FileViewFinder {
            return new class($app['files'], $app['config']['view.paths']) extends FileViewFinder
            {
                /**
                 * رفض أسماء العرض التي تحتوي رموزًا غير مسموحة في أسماء ملفات ويندوز.
                 */
                public function find($name)
                {
                    if (is_string($name) && strpbrk($name, '<>?*|"') !== false) {
                        throw new InvalidArgumentException("View [{$name}] not found.");
                    }

                    return parent::find($name);
                }
            };
        });
    }

    /**
     * تهيئة الخدمات بعد الإقلاع.
     */
    public function boot(): void
    {
        // 🌍 قيمة افتراضية لوسيط اللغة في الروابط، حتى يعمل توليد الروابط
        //    (route('home') ... إلخ) خارج دورة الطلب أيضاً (الاختبارات، الأوامر).
        //    ويتم تجاوزها لكل طلب فعلي داخل middleware SetLocale بحسب بادئة الرابط.
        URL::defaults(['locale' => (string) config('locales.default', 'nl')]);

        RateLimiter::for('reservation-submit', function (Request $request) {
            return Limit::perHour(3)->by($request->ip())->response(
                fn () => response()->json(
                    ['message' => 'لقد تجاوزت الحد الأقصى للمحاولات. جرب بعد ساعة.'],
                    429,
                ),
            );
        });

        RateLimiter::for('contact-submit', function (Request $request) {
            return Limit::perHour(5)->by($request->ip());
        });

        RateLimiter::for('checkout-submit', function (Request $request) {
            return Limit::perHour(2)->by($request->ip());
        });

        // $this->loadViewsFrom(__DIR__ . '/../../resources/views/layouts', 'layouts');
    }
}
