<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        RateLimiter::for('reservation-submit', function (Request $request) {
            return Limit::perHour(3)->by($request->ip())->response(
                fn () => response()->json(['message' => 'لقد تجاوزت الحد الأقصى للمحاولات. جرب بعد ساعة.'], 429)
            );
        });

        RateLimiter::for('contact-submit', function (Request $request) {
            return Limit::perHour(5)->by($request->ip());
        });

        RateLimiter::for('checkout-submit', function (Request $request) {
            return Limit::perHour(2)->by($request->ip());
        });
    }
}
