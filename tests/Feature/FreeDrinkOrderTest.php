<?php

use App\Exceptions\OrderException;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Services\OrderService;

it('lines up drinks for the fixed free-drink item even if the client sends nothing', function () {
    $drinks = MenuCategory::factory()->create(['is_drinks_category' => true]);
    $cola = MenuItem::factory()->for($drinks, 'category')->create(['price' => 2.50, 'is_available' => true]);

    $main = MenuItem::factory()->create([
        'offers_free_drink' => true,
        'free_drink_qty' => 2,
        'free_drink_policy' => 'per_unit',
        'free_drink_item_ids' => [$cola->id],
        'is_available' => true,
    ]);

    $order = app(OrderService::class)->createOrder(
        ['type' => 'pickup', 'customer_name' => 'A', 'customer_phone' => '1'],
        [['menu_item_id' => $main->id, 'quantity' => 2]], // ← بلا اختيارات
    );

    $options = $order->items()->first()->options;
    expect($options['free_drinks'][0]['id'])->toBe($cola->id)
        ->and($options['free_drinks'][0]['qty'])->toBe(4)   // 2 × 2
        ->and((float) $options['free_drinks'][0]['price'])->toBe(0.0)
        ->and((float) $order->subtotal)->toBe((float) $main->price * 2); // لا تأثير على السعر
});

it('rejects a drink that is not allowed for the item', function () {
    $drinks = MenuCategory::factory()->create(['is_drinks_category' => true]);
    $a = MenuItem::factory()->for($drinks, 'category')->create();
    $b = MenuItem::factory()->for($drinks, 'category')->create();

    $main = MenuItem::factory()->create([
        'offers_free_drink' => true,
        'free_drink_item_ids' => [$a->id, $b->id],
    ]);

    app(OrderService::class)->createOrder(
        ['type' => 'pickup', 'customer_name' => 'A', 'customer_phone' => '1'],
        [['menu_item_id' => $main->id, 'quantity' => 1, 'free_drink_selections' => [999999]]],
    );
})->throws(OrderException::class);
