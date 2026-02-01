<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TicketPurchase>
 */
class TicketPurchaseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'purchaser_email' => fake()->email(),
            'ticket_id' => fake()->uuid(),
            'discord_user' => strtolower(fake()->firstName())
        ];
    }
}
