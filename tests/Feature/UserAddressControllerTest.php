<?php

namespace Tests\Feature;

use App\Enums\AddressType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class UserAddressControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_save_address()
    {
        $user = User::factory()->create();
        Auth::login($user);

        $data = [
            'type' => AddressType::DELIVERY->value,
            'zip' => '1111',
            'city' => 'city',
            'street' => 'street',
            'house' => '11',
            'info' => 'information',
        ];

        $response = $this->postJson('/api/user_addresses', $data);

        $response->assertStatus(201);
        $response->assertJson([
            'user_id' => $user->id,
            'address_type' => AddressType::DELIVERY->value,
            'zip_code' => '1111',
            'city' => 'city',
            'line1' => 'street',
            'line2' => 'null',
            'building_number' => '11',
            'additional_information' => 'information',
        ]);
    }

    /** @test */
    public function user_gets_error_message_if_data_is_invalid()
    {
        $user = User::factory()->create();
        Auth::login($user);

        $data = [
            'type' => 'invalidType',
            'zip' => 'string',
            'city' => '',
            'street' => '',
            'house' => '',
            'info' => 123,
        ];

        $response = $this->postJson('/api/user_addresses', $data);

        // assert the response status code is 422 for validation errors
        $response->assertStatus(422);

        // assert appropriate error messages
        $response->assertJsonValidationErrors([
            'zip',
            'city',
            'street',
            'house',
            'info',
            'type',
        ]);
    }
}
