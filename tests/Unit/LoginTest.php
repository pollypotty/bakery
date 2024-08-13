<?php

namespace Tests\Unit;

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
    public function a_user_can_log_in()
    {
        $user = User::factory()->create([
            'email' => 'test@test.com',
            'password' => bcrypt('password')
        ]);

        $login = $this->authService->login([
            'email' => 'test@test.com',
            'password' => 'password'
        ]);

        $this->assertTrue($login);
    }

    /** @test */
    public function a_user_can_not_log_in_with_invalid_data()
    {
        $user = User::factory()->create([
            'email' => 'test@test.com',
            'password' => bcrypt('password')
        ]);

        $login = $this->authService->login([
            'email' => 'test@test.com',
            'password' => 'wrongPassword'
        ]);

        $this->assertFalse($login);
    }
}
