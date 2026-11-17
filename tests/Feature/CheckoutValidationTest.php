<?php

use App\Livewire\MenuWithCart;
use App\Models\MenuItem;
use App\Services\OrderService;
use App\Services\ReservationService;
use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;

/**
 * التحقق من أن صيغة الرمز البريدي لا تُفرض إلا عند اختيار "توصيل"،
 * ولا تظهر أخطاؤها مع الاستلام أو تناول الطعام داخل المطعم.
 */
function runCheckoutValidation(string $orderType, string $postalCode): Testable
{
    $item = MenuItem::factory()->create(['price' => 10, 'is_available' => true]);

    $component = Livewire::test(MenuWithCart::class)
        ->set('cart', [
            $item->id => [
                'id' => $item->id,
                'name' => 'Test Dish',
                'price' => 10.0,
                'quantity' => 1,
            ],
        ])
        ->set('order_type', $orderType)
        ->set('customer_name', 'Test Customer')
        ->set('customer_phone', '0612345678')
        ->set('delivery_address', 'Some street 1')
        ->set('delivery_city', 'Amsterdam')
        ->set('delivery_postal_code', $postalCode);

    try {
        $component->call('checkout', app(ReservationService::class), app(OrderService::class));
    } catch (Throwable $e) {
        // فشل التحقق يرمي استثناءً؛ نتجاهله ونفحص حقيبة الأخطاء.
    }

    return $component;
}

it('لا يتحقق من صيغة الرمز البريدي عند الاستلام', function () {
    runCheckoutValidation('pickup', 'not-a-postcode')
        ->assertHasNoErrors(['delivery_postal_code']);
});

it('لا يتحقق من صيغة الرمز البريدي عند تناول الطعام في المطعم', function () {
    runCheckoutValidation('dine_in', 'not-a-postcode')
        ->assertHasNoErrors(['delivery_postal_code']);
});

it('يفرض صيغة الرمز البريدي عند التوصيل', function () {
    runCheckoutValidation('delivery', 'not-a-postcode')
        ->assertHasErrors(['delivery_postal_code']);
});
