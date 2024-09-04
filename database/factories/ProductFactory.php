<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->realText(20),
            'description' => fake()->realTextBetween(120),
            'price' => fake()->numberBetween(5,20),
            'prepare_days' => fake()->numberBetween(2,5),
            'availability' => fake()->numberBetween(0,1),
        ];
    }
}
