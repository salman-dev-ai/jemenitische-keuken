<?php

namespace Database\Factories;

use App\Enums\AllergenType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * Responsibility: يولّد عناصر حساسية/علامات غذائية تجريبية للتطوير والاختبار.
 */
class AllergenFactory extends Factory
{
    public function definition(): array
    {
        $name = ucfirst($this->faker->unique()->word());

        return [
            'slug' => Str::slug($name),
            'name' => [
                'ar' => $name,
                'en' => $name,
                'nl' => $name,
            ],
            'type' => $this->faker->randomElement(AllergenType::cases()),
            'icon' => null,
            'image_path' => null,
            'is_active' => true,
            'sort_order' => $this->faker->numberBetween(1, 50),
        ];
    }
}
