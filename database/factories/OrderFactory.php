<?php

namespace Database\Factories;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Responsibility: Generates sample order records with random types and financial statuses.
 */

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $subtotal = $this->faker->randomFloat(2, 10, 100);
        $tax = round($subtotal * 0.1);
        $total = $subtotal + $tax;

        return [
            //ginreat number order aotmtic from model
            'customer_name' => $this->faker->name(),
            'customer_phone' => $this->faker->phoneNumber(),
            'customer_email' => $this->faker->safeEmail(),
            'type' => $this->faker->randomElement(OrderType::cases()),
            'status' => $this->faker->randomElement(OrderStatus::cases()),
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
            'payment_status' => $this->faker->randomElement(['unpaid', 'paid']),
            'payment_method' => $this->faker->randomElement(['iDELA', 'Credit Card', 'Cash']),
            'notes' => $this->faker->optional(0.3)->sentence(),

        ];
    }
}
