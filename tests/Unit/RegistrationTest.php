<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\Auth\RegistrationService;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test
     * @throws Exception
     */
    public function a_user_can_register()
    {
        $service = new RegistrationService();

        $data = [
            'email' => 'john@example.com',
            'firstName' => 'John',
            'lastName' => 'Doe',
            'password' => 'password',
        ];

        $user = $service->register($data);

        // assert that the user was created in the database
        $this->assertInstanceOf(User::class, $user);
        $this->assertDatabaseHas('users', ['email' => 'john@example.com']);

        // assert that password was hashed
        $this->assertTrue(Hash::check('password', $user->password));
    }
}
