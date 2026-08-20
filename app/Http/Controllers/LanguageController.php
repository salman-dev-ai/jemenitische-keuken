<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class LanguageController extends Controller
{
    public function switch(Request $request, string $locale): RedirectResponse
    {
        $locale = in_array($locale, ['ar', 'en', 'nl']) ? $locale : config('app.locale');

        session(['locale' => $locale]);
        App::setLocale($locale);

        return redirect()->back();
    }
}
