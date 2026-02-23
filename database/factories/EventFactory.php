<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $yesterday = Carbon::yesterday();
        
        $start = $yesterday->minute < 30 
            ? $yesterday->copy()->hour($yesterday->hour + 1)->minute(0)->second(0)
            : $yesterday->copy()->hour($yesterday->hour + 2)->minute(0)->second(0);

        return [
            'name' => 'CTF '.fake()->words(2, true),
            'start_time' => $start,
            'end_time' => $start->copy()->addHours(4),
            'simulate_activity' => false
        ];
    }
}
