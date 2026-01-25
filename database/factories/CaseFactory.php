<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class CaseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $now = $now = Carbon::now();
        
        $age = fake()->numberBetween(20, 60);

        return [
            'name' => fake()->name(),
            'age' => $age,
            'characteristics' => fake()->paragraph(),
            'disappearance_details' => fake()->sentence(),
            'date_of_birth' => $now->subYears($age),
            'height' => fake()->numberBetween(4, 6),
            'weight' => fake()->numberBetween(50, 100),
            'missing_from' => fake()->word(),
            'missing_since' => $now->subDays(20),
            'missing_since_note' => "+- 2 days",
            'notes' => fake()->paragraph(),
            'url' => fake()->url()
        ];
    }
}
