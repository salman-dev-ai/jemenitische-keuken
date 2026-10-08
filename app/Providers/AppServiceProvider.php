<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\RestaurantSetting;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

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
    }

    /**
     * تهيئة الخدمات بعد الإقلاع.
     */
    public function boot(): void
    {
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

        $this->loadViewsFrom(__DIR__ . '/../../resources/views/layouts', 'layouts');
    }
}
