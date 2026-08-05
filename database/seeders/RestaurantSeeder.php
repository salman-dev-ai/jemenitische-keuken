<?php

namespace Database\Seeders;

use App\Models\RestaurantSetting;
use Illuminate\Database\Seeder;

class RestaurantSeeder extends Seeder
{
  /**
     * Run the database seeds.
     * Responsibility: Seeds the initial real-world configuration .
     */
    public function run(): void
    {
        RestaurantSetting::create([
            'name'=>[
                'ar'=>'المطبخ اليمني ',
                'nl'=>'Jemenitische Keuken',
                'en'=>'Yemeni Kitchen',
            ],
            'phone' => '+31 6 1234 5678',
            'whatsapp' => '+31 6 1234 5678',
            'email' => 'info@yemenikitchen.nl',
            'address' => 'Amsterdamstraat 123',
            'city' => 'Amsterdam',
            'postal_code' => '1011 AB',
            'opening_hours' => [
                'Monday' => 'Closed',
                'Tuesday' => '12:00 - 22:00',
                'Wednesday' => '12:00 - 22:00',
                'Thursday' => '12:00 - 22:00',
                'Friday' => '13:00 - 23:00',
                'Saturday' => '13:00 - 23:00',
                'Sunday' => '12:00 - 22:00',
            ],
            'accepts_reservations' => true,
            'accepts_online_orders' => true,

        ]);
    }
}
