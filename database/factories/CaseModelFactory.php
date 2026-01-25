<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class CaseModelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $now = $now = Carbon::now();
        
        $age = fake()->numberBetween(18, 67);

        return [
            'name' => fake()->name(),
            'age' => $age,
            'characteristics' => fake()->paragraph(),
            'disappearance_details' => fake()->sentence(),
            'date_of_birth' => $now->copy()->subYears($age),
            'height' => fake()->numberBetween(4, 6),
            'weight' => fake()->numberBetween(50, 100),
            'missing_from' => fake()->word(),
            'missing_since' => $now->subDays(rand(1, 30)),
            'missing_since_note' => "+- 2 days",
            'notes' => fake()->paragraph(),
            'source_url' => fake()->url()
        ];
    }
}
