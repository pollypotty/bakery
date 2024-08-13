<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\Auth\RegistrationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_register()
    {
        $response = $this->post('/registration', [
            'email' => 'john@example.com',
            'firstName' => 'John',
            'lastName' => 'Doe',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        // Retrieve the user from the database
        $user = User::where('email', 'john@example.com')->first();

        // Assert that the user was created in the database
        $this->assertNotNull($user);

        // Assert that the user is authenticated
        $this->assertTrue(Hash::check('password', $user->password));
        $this->assertAuthenticatedAs($user);

        // Assert that the response redirects to the /profile URL
        $response->assertRedirect('/profile');
    }

    /** @test */
    public function it_shows_errors_for_invalid_registration_data()
    {
        // Simulate registration with invalid data
        $response = $this->post('/registration', [
            'email' => 'johnexample',
            'firstName' => '',
            'lastName' => '',
            'password' => 'short',
            'password_confirmation' => 'different',
        ]);

        // Assert that response contains validation errors
        $response->assertSessionHasErrors([
            'email',
            'firstName',
            'lastName',
            'password',
        ]);
    }

    /** @test */
    public function it_shows_error_for_duplicate_email()
    {
        // Create a user with the same email
        User::factory()->create(['email' => 'john@example.com']);

        // Attempt to register with the same email
        $response = $this->post('/registration', [
            'email' => 'john@example.com',
            'firstName' => 'John',
            'lastName' => 'Doe',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        // Log the redirect location for debugging
        $actualRedirectUrl = $response->headers->get('Location');
        Log::info('Redirected to in duplicate email: ' . $actualRedirectUrl);

        // $response->assertRedirect('/registration');

        // Assert that response contains validation errors for email
        $response->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function it_handles_general_exceptions()
    {
        // Mock the registration service to throw a general exception
        $this->mock(RegistrationService::class, function ($mock) {
            $mock->shouldReceive('register')->andThrow(new \Exception(config('messages.registration_error')));
        });

        $response = $this->post('/registration', [
            'email' => 'john@example.com',
            'firstName' => 'John',
            'lastName' => 'Doe',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        // Assert redirection to registration page with error message
        $response->assertRedirect('/registration');
        $response->assertSessionHasErrors(['message' => config('messages.registration_error')]);
    }
}
