<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    /** @test */
    public function a_user_can_logout()
    {
        $user = User::factory()->create();
        Auth::login($user);

        $this->assertAuthenticatedAs($user);

        $response = $this->post('/logout');

        $response->assertStatus(204);
        $this->assertGuest();
    }
}
