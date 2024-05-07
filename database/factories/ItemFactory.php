<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
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
            'description' => implode(' ',fake()->sentences(10)),
            'made_in' => fake()->year(),
            'auction' => false,
            // 'image' => fake()->imageUrl(),
            // 'other_images' => ???,
        ];
    }
}
