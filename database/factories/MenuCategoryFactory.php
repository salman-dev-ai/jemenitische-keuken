<?php

namespace Database\Factories;

use App\Models\MenuCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;


/**
 * Responsibility: Generates dummy menu categories with translations.
 */

/**
 * @extends Factory<MenuCategory>
 */
class MenuCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $nameEn = $this->faker->unique()->words(2, true);

        return [


            'name' => [
                'en' => ucfirst($nameEn),
                'ar' => 'قسم' . $this->faker->word(),
                'nl' => 'Categorie' . $this->faker->word(),

            ],
            'slug' => Str::slug($nameEn),
            'description' => [
                'en' => $this->faker->sentence(),
                'ar' => 'وصف تجريبي للقسم باللغة العربية.',
                'nl' => 'Korte beschrijving in het Nederlands.',
            ],
            'is_available' => true,
            'sort_order' => $this->faker->numberBetween(1, 10),

        ];
    }
}
