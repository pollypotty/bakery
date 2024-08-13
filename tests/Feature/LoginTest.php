<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\Auth\AuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
     use RefreshDatabase;

    protected AuthService $authService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->authService = app(AuthService::class);
    }

    /** @test */
    public function a_user_can_login()
    {
        $user = User::factory()->create([
            'email' => 'test@test.com',
            'password' => bcrypt('password')
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect('/profile');
    }

    /** @test */
    public function a_user_can_not_login_with_invalid_data()
    {
        $user = User::factory()->create([
            'email' => 'test@test.com',
            'password' => bcrypt('password')
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'differentPassword'
        ]);

        $this->assertGuest();

//        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('login_err');
    }
}
