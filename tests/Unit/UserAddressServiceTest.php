<?php

namespace Tests\Unit;

use App\Enums\AddressType;
use App\Models\User;
use App\Models\UserAddress;
use App\Services\UserAddressService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class UserAddressServiceTest extends TestCase
{
    use RefreshDatabase;

    /** @test
     * @throws \Exception
     */
    public function user_can_save_new_address()
    {
        // create a user and log them in
        $user = User::factory()->create();
        Auth::login($user);

        // dependency injection of service
        $service = app(UserAddressService::class);

        $data = [
            'type' => AddressType::DELIVERY->value,
            'zip' => '1111',
            'city' => 'city',
            'street' => 'street',
            'house' => '11',
            'info' => 'information',
        ];

        // give the service valid data
        $address = $service->store($data);

        // assert that the address was created in the database
        $this->assertInstanceOf(UserAddress::class, $address);
        $this->assertDatabaseHas('user_addresses', [
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
}
