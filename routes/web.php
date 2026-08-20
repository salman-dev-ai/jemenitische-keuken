<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\LanguageController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});



// التوثيق الرسمي يفرض ربط الاسم بالشكل التالي:
// Route::get('lang/switch', [LanguageController::class, 'switch'])->name('lang.switch');
Route::get('lang/switch/{locale}', [LanguageController::class, 'switch'])->name('lang.switch');
