<?php

namespace Tests\Feature\Http\Controllers\Auth;

use App\Services\Auth\OAuthService;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;
use Mockery;
use Tests\TestCase;
use Laravel\Socialite\Contracts\User as SocialUser;

class OAuthControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function controller_redirects_to_google_login()
    {
        $response = $this->get('/auth/google/login');

        $response->assertRedirect();
        $this->assertEquals('login', Session::get('auth_action'));
    }

    /** @test */
    public function controller_redirects_to_google_registration()
    {
        $response = $this->get('/auth/google/register');

        $response->assertRedirect();
        $this->assertEquals('register', Session::get('auth_action'));
    }

    /** @test */
    public function controller_handles_successful_google_registration()
    {
        // mock object for OAuthService
        $oauthServiceMock = Mockery::mock(OAuthService::class);

        // mock successful registration scenario to 'handleGoogleCallback' function
        $oauthServiceMock->shouldReceive('handleGoogleCallback')
            ->andReturn(['status' => 'success', 'redirect' => '/profile']);

        // bind the mocked 'OAuthService' to the application instance
        $this->app->instance(OAuthService::class, $oauthServiceMock);

        // simulate that the user initiates the registration process
        Session::put('auth_action', 'register');

        // perform HTTP get request to '/auth/google/callback' endpoint, this triggers the controller's logic to process the OAuth callback
        $response = $this->get('/auth/google/callback');

        // check if the response redirects to '/profile'
        $response->assertRedirect('/profile');
    }

    /** @test */
    public function controller_handles_unsuccessful_google_registration()
    {
        $oauthServiceMock = Mockery::mock(OAuthService::class);
        $oauthServiceMock->shouldReceive('handleGoogleCallback')
            ->andReturn([
                'status' => 'error',
                'redirect' => '/registration',
                'errors' => ['registeredEmail' => config('messages.registered_email')]
            ]);
        $this->app->instance(OAuthService::class, $oauthServiceMock);

        Session::put('auth_action', 'register');

        $response = $this->get('/auth/google/callback');

        $response->assertRedirect('/registration');
        $response->assertSessionHasErrors('registeredEmail');
    }

    /** @test */
    public function controller_handles_successful_google_login()
    {
        $oauthServiceMock = Mockery::mock(OAuthService::class);
        $oauthServiceMock->shouldReceive('handleGoogleCallback')
            ->andReturn(['status' => 'success', 'redirect' => '/profile']);
        $this->app->instance(OAuthService::class, $oauthServiceMock);

        Session::put('auth_action', 'login');

        $response = $this->get('/auth/google/callback');

        $response->assertRedirect('/profile');
    }

    /** @test */
    public function controller_handles_unsuccessful_google_login()
    {
        $oauthServiceMock = Mockery::mock(OAuthService::class);
        $oauthServiceMock->shouldReceive('handleGoogleCallback')
            ->andReturn([
                'status' => 'error',
                'redirect' => '/login',
                'errors' => ['notFound' => config('messages.user_not_found')]
            ]);
        $this->app->instance(OAuthService::class, $oauthServiceMock);

        Session::put('auth_action', 'login');

        $response = $this->get('/auth/google/callback');

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('notFound');
    }

    /** @test */
    public function controller_handles_google_callback_exception()
    {
        $oauthServiceMock = Mockery::mock(OAuthService::class);
        $oauthServiceMock->shouldReceive('handleGoogleCallback')->andThrow(new Exception ());
        $this->app->instance(OAuthService::class, $oauthServiceMock);

        Session::put('auth_action', 'login');

        Log::shouldReceive('error')->once();

        $response = $this->get('/auth/google/callback');

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors(['failedAuth' => config('messages.google_auth_failed')]);
    }
}
