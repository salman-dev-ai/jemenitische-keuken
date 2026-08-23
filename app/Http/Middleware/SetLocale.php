<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $defaultLocale = config('app.locale', 'ar');
        $availableLocales = ['ar', 'en', 'nl'];

        if (session()->has('locale')) {
            $locale = session('locale');
        } else {
            $preferred = $request->getPreferredLanguage($availableLocales);
            $locale = $preferred ?: $defaultLocale;
            session()->put('locale', $locale);
        }

        if (! in_array($locale, $availableLocales)) {
            $locale = $defaultLocale;
        }

        app()->setLocale($locale);
        app()->setFallbackLocale('en');

        return $next($request);
    }
}
