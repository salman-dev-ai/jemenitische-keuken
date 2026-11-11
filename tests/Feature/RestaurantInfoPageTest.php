<?php

use App\Models\Allergen;
use App\Models\DeliveryZone;
use App\Models\RestaurantSetting;

it('renders the restaurant information page with hours, allergens and delivery zones', function () {
    $this->withoutVite();

    // صف واحد لأوقات الدوام حتى تظهر بطاقة الساعات بمحتوى حقيقي.
    RestaurantSetting::current()->update([
        'opening_hours' => [
            [
                'day' => 'monday',
                'is_closed' => false,
                'opens_time' => '9:00',
                'opens_period' => 'am',
                'closes_time' => '11:00',
                'closes_period' => 'pm',
            ],
        ],
    ]);

    DeliveryZone::create([
        'region_name' => 'Amsterdam Centrum',
        'postal_code' => '1012 NK',
        'delivery_cost' => 2.50,
        'minimum_order' => 20.00,
    ]);

    // القاموس الافتراضي يُزرع أثناء الترحيل، فنستخدم عناصره الجاهزة.
    $gluten = Allergen::where('slug', 'gluten')->firstOrFail();
    $halal = Allergen::where('slug', 'halal')->firstOrFail();

    $this->get(route('restaurant-info'))
        ->assertOk()
        ->assertSee(route('restaurant-info'))
        ->assertSee(__('info.page_title'))
        ->assertSee(__('info.allergens_title'))
        ->assertSee(__('info.allergens_subtitle', ['count' => Allergen::query()->active()->count()]))
        ->assertSee($gluten->localized_name)
        ->assertSee($halal->localized_name)
        ->assertSee('Amsterdam Centrum')
        ->assertSee('1012 NK')
        ->assertSee(__('delivery.minimum_order'))
        ->assertSee('20.00')
        ->assertSee('9:00');
});

it('shows the allergens empty state when no allergen is active', function () {
    $this->withoutVite();

    Allergen::query()->update(['is_active' => false]);

    $this->get(route('restaurant-info'))
        ->assertOk()
        ->assertSee(__('info.allergens_empty'));
});
