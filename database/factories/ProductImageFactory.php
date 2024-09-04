<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductImage>
 */
class ProductImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static $imageCounter = 1;

        $imagePaths = [
            '/images/product1.jpg',
            '/images/product2.jpg',
            '/images/product3.jpg',
            '/images/product4.jpg',
            '/images/product5.jpg',
        ];

         // get the current image path based on the counter and then increment the counter
        $currentImagePath = $imagePaths[$imageCounter % count($imagePaths)];
        $imageCounter++;

        return [
            'product_id' => fake()->numberBetween(1, 5),
            'image_path' =>$currentImagePath,
            'feature_image' => fake()->boolean(),
        ];
    }
}
