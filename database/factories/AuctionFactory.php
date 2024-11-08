<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Auction>
 */
class AuctionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $deadline = fake()->dateTimeBetween('-4 months', '+1 months')->format('Y-m-d');
        return [
            'price' => fake()->randomNumber(5, true),
            'description' => implode(' ', fake()->sentences(3)),
            'deadline' => $deadline,
            'opened' => $deadline >= now()->format('Y-m-d'),
        ];
    }
}
