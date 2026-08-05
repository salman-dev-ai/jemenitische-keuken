<?php

namespace Database\Factories;

use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OrderItem>
 */

/**
 * Responsibility: Generates relational order item snapshots.
 */
class OrderItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $quantity = $this->faker->numberBetween(1, 4);
        $unitPrice = $this->faker->randomFloat(2, 10, 30);

        return [
            //
            'order_id' => Order::factory(),
            'menu_item_id' => MenuItem::factory(),
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'total_price' => $quantity * $unitPrice,
            // اجعل القيمة موجودة باحتمال 20%. optional
            'options' => $this->faker->optional(0.2)->randomElement([['extra_sauce' => true], ['no_onions' => true]]),
        ];
    }
}
