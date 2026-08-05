<?php

namespace Database\Factories;

use App\Enums\ReservationStatus;
use App\Models\Reservation;
use App\Models\Table;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Responsibility: Generates realistic reservation scenarios across past, present, and future dates.
 */

/**
 * @extends Factory<Reservation>
 */
class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'table_id'=> Table::factory(),
            'guest_name'=>$this->faker->name(),
            'guest_email'=>$this->faker->safeEmail(),
            'guest_phone'=>$this->faker->phoneNumber(),
            'party_size'=>$this->faker->numberBetween(1, 10),
            'reservation_date'=>$this->faker->dateTimeBetween('now', '+2 weeks')->format('Y-m-d'),

           'reservation_time'=>$this->faker->randomElement(['13:00', '14:30', '18:00', '19:00',  '21:00']),
            'special_requests'=>$this->faker->optional(0.4)->sentence(),
            'status'=>$this->faker->randomElement(ReservationStatus::cases()),

            ];
    }
}
