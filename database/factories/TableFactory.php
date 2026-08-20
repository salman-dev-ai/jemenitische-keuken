<?php

namespace Database\Factories;

use App\Models\Table;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Table>
 */

/**
 * Responsibility: Generates physical table records with capacity constraints.
 */
class TableFactory extends Factory
{

    /**
     *هذا السطر هو الأهم هنا! هو عبارة عن "عداد ساكن"
    *(static) يبدأ برقم 1.فائدته أنه يتذكر رقمه الحالي ولا يضيع عند توليد طاولة جديدة، مما يسمح لنا بإنشاء أرقام طاولات متسلسلة
    * (الطاولة 1، ثم 2، ثم 3...)
    * بدلاً من توليد أرقام عشوائية قد
     */
    private static int $tableCounter=1;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
        // يأخذ رقم العداد الحالي لتسمية الطاولة، ثم يزيده بمقدار 1 للطاولة التالية.
        'table_number'=> 'T-'.str_pad((string) self::$tableCounter++,2,'0',STR_PAD_LEFT),
        'capacity'=>$this ->faker->randomElement([2,4,6,8,10]),
        'location_zone' => $this ->faker->randomElement(['Main Hall','Terrace','Family Section','VIP Area']),
        'is_available'=>true,
        ];
    }
}
