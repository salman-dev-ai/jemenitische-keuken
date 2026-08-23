<?php

namespace Database\Seeders;

use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Seeder;

/**
 * Responsibility: Master Seeder class orchestrating database population for local development.
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. زرع الإعدادات العامة للمطعم
        $this->call(RestaurantSeeder::class);

        // 2. زرع 5 أقسام منيو، وكل قسم يحتوي على 6 أطباق
        MenuCategory::factory(5)
            // لكل MenuCategory يتم إنشاؤه，has
            //  أنشئ 6 MenuItem من خلال العلاقة المسماة menuItems.
            //
            ->has(MenuItem::factory()->count(6), 'menuItems')
            ->create();

        // 5. زرع 10 طلبات تجريبية مع عناصرها
        Order::factory(10)->create()->each(function (Order $order) {
            // اجلب الـ MenuItems بترتيب عشوائي.  inRandomOrder
            // خذ عدد عشوائي من 1 إلى 3 أطباق. take(rand(1, 3))
            // تنفذ الاستعلام وتعيد Collection. get()
            $randomItems = MenuItem::inRandomOrder()->take(rand(1, 3))->get();

            // نمر على كل طبق تم اختياره.
            foreach ($randomItems as $item) {
                OrderItem::factory()->create([
                    'order_id' => $order->id,
                    'menu_item_id' => $item->id,
                    'unit_price' => $item->price,
                    // هنا نعمل  override للـ total_price و quantity لتكون ثابتة، على سبيل المثال 2 لكل طبق.
                    'total_price' => $item->price * 2,
                    'quantity' => 2,
                ]);
            }
        });
    }
}
