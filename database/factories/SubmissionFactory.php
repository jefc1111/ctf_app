<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\SubmissionDecisionStatus;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Submission>
 */
class SubmissionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->word(),
            'content' => fake()->paragraph(),
            'explanation' => fake()->paragraph(),
            'draft' => fake()->boolean(20), // The arg tweaks the likelihood of getting `true`
            'decision_status' => fake()->randomElement(SubmissionDecisionStatus::names()),
            'decision_supporting_evidence' => fake()->paragraph()
        ];
    }
}
