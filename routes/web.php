<?php

use App\Http\Controllers\LanguageController;
use App\Models\RestaurantSetting;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $settings=RestaurantSetting::first();
    return view('welcome',compact('settings'));
})->name('home');

// التوثيق الرسمي يفرض ربط الاسم بالشكل التالي:
// Route::get('lang/switch', [LanguageController::class, 'switch'])->name('lang.switch');
Route::get('lang/switch/{locale}', [LanguageController::class, 'switch'])->name('lang.switch');
