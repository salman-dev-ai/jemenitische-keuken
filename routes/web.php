<?php

use App\Http\Controllers\LanguageController;
use App\Livewire\ShowDeliveryZones;
use App\Models\RestaurantSetting;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
     $settings = RestaurantSetting::current();

    return view('welcome', compact('settings'));
})->name('home');


Route::get('/delivery-zones', ShowDeliveryZones::class)
    ->name('delivery.zones');

// التوثيق الرسمي يفرض ربط الاسم بالشكل التالي:
// Route::get('lang/switch', [LanguageController::class, 'switch'])->name('lang.switch');
Route::get('lang/switch/{locale}', [LanguageController::class, 'switch'])->name('lang.switch');
