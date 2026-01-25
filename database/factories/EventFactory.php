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
        $now = $now = Carbon::now();
        
        $start = $now->minute < 30 
            ? $now->copy()->hour(11)->minute(0)->second(0)
            : $now->copy()->hour(12)->minute(0)->second(0);

        return [
            'name' => 'TL CTF '.fake()->words(2, true),
            'start_time' => $start,
            'end_time' => $start->addHours(4)
        ];
    }
}
