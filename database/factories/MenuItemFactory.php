<?php

namespace Database\Factories;

use App\Models\MenuCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * Responsibility: Generates realistic dummy dishes with varied pricing and allergens.
 */
class MenuItemFactory extends Factory
{
    public function definition(): array
    {
        // create 3 word random and unique
        $nameEn = $this->faker->unique()->words(3, true);

        return [
            // إذا لم نمرر قسم محدد، سيقوم المصنع بإنشاء قسم جديد تلقائياً
            'menu_category_id' => MenuCategory::factory(),
            'name' => [
                // make char fist upper
                'en' => ucfirst($nameEn),
                'ar' => 'وجبة '.$this->faker->word(),
                'nl' => 'Gerecht '.$this->faker->word(),
            ],
            // حول الاسم الإنجليزي إلى رابط صديق لمحركات البحث (مثال: chicken-tikka-masala)
            'slug' => Str::slug($nameEn),
            'description' => [
                // genreat 6 word
                'en' => $this->faker->sentence(6),
                'ar' => 'وصف لذيذ لهذه الوجبة اليمنية الرائعة.',
                'nl' => 'Lekker Jemenitisch gerecht.',
            ],
            // ملاحظة: مسببات الحساسية تُربط الآن بجدول allergens عبر علاقة متعدد-لمتعدد
            // (انظر AllergenSeeder) ولا تُخزَّن كنص حر داخل الطبق.
            'price' => $this->faker->randomFloat(2, 12, 35), // أسعار بين 12 و 35 يورو
            'is_available' => $this->faker->boolean(90), // 90% من الأطباق متاحة
            'is_featured' => $this->faker->boolean(20), // 20% فقط أطباق مميزة للصفحة الرئيسية
            'is_spicy' => $this->faker->boolean(30),
        ];
    }
}
