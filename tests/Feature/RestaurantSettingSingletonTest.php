<?php

use App\Models\RestaurantSetting;
use Illuminate\Support\Facades\Cache;

/*
|--------------------------------------------------------------------------
| Restaurant settings (singleton)
|--------------------------------------------------------------------------
|
| Guards against RestaurantSetting::current() creating a new settings row
| every time the cache is cleared, which produced duplicate records in the
| admin panel table.
|
*/

/**
 * إدخال صف الإعدادات كما هو موجود في قاعدة البيانات (id ليس 1).
 *
 * @param  array<string, mixed>  $attributes
 */
function insertRestaurantSettingsRow(int $id, array $attributes = []): void
{
    RestaurantSetting::query()->insert(array_merge([
        'id' => $id,
        'name' => json_encode(['ar' => 'المطبخ اليمني', 'en' => 'Yemeni Kitchen']),
        'phone' => '771924870',
        'email' => 'info@example.com',
        'whatsapp' => '0612345678',
        'address' => 'Damrak 45',
        'city' => 'Amsterdam',
        'postal_code' => '1012 NK',
        'vat_rate' => 10.00,
        'max_quantity_per_item' => 50,
        'created_at' => now(),
        'updated_at' => now(),
    ], $attributes));
}

beforeEach(function () {
    Cache::forget('restaurant_settings');
});

test('it returns the existing row when its id is not 1', function () {
    insertRestaurantSettingsRow(22);

    $settings = RestaurantSetting::current();

    expect($settings->id)->toBe(22)
        ->and($settings->phone)->toBe('771924870')
        ->and(RestaurantSetting::query()->count())->toBe(1);
});

test('it does not create a duplicate row when the cache is cleared', function () {
    insertRestaurantSettingsRow(22);

    RestaurantSetting::current();
    Cache::forget('restaurant_settings');
    RestaurantSetting::current();
    Cache::forget('restaurant_settings');
    RestaurantSetting::current();

    expect(RestaurantSetting::query()->count())->toBe(1);
});

test('it reads the configured values instead of the defaults', function () {
    insertRestaurantSettingsRow(22, ['vat_rate' => 10.00]);

    expect(RestaurantSetting::vatRate())->toBe(0.1);
});

test('it creates exactly one default row when the table is empty', function () {
    RestaurantSetting::current();
    Cache::forget('restaurant_settings');
    RestaurantSetting::current();

    $rows = RestaurantSetting::query()->get();

    expect($rows)->toHaveCount(1)
        ->and($rows->first()->phone)->toBe('+31 20 000 0000');
});
