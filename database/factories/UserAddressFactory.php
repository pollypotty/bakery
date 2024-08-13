<?php

namespace Database\Factories;

use App\Enums\AddressType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserAddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $user = User::query()->inRandomOrder()->first() ?: User::factory()->create();
        return [
            'user_id' => $user->id,
            'address_type' => fake()->randomElement([
                AddressType::BILLING->value,
                AddressType::BILLING_AND_DELIVERY->value,
                AddressType::DELIVERY->value,
                AddressType::ONE_TIME->value,
            ]),
            'zip_code' => fake()->numberBetween(1000,9999),
            'city' => fake()->city(),
            'line1' => fake()->realTextBetween(10,30),
            'line2' => fake()->realTextBetween(1,30),
            'building_number' => fake()->numberBetween(1,150),
            'additional_information' => fake()->realTextBetween(1,50),
        ];
    }
}
