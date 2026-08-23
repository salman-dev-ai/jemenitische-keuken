<?php

use App\Http\Controllers\LanguageController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// التوثيق الرسمي يفرض ربط الاسم بالشكل التالي:
// Route::get('lang/switch', [LanguageController::class, 'switch'])->name('lang.switch');
Route::get('lang/switch/{locale}', [LanguageController::class, 'switch'])->name('lang.switch');
