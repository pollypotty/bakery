<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\OAuthService;
use Exception;
use Illuminate\Foundation\Application;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse;

class OAuthController extends Controller
{
    protected OAuthService $oauthService;

    public function __construct(OAuthService $oauthService)
    {
        $this->oauthService = $oauthService;
    }

    public function redirectToGoogleLogin(): RedirectResponse|\Illuminate\Http\RedirectResponse
    {
        Session::put('auth_action', 'login');
        return Socialite::driver('google')->redirect();
    }

    public function redirectToGoogleRegister(): RedirectResponse|\Illuminate\Http\RedirectResponse
    {
        Session::put('auth_action', 'register');
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(): Application|Redirector|\Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse
    {
        $authAction = Session::get('auth_action');

        try {
            $result = $this->oauthService->handleGoogleCallback($authAction);

            return redirect($result['redirect'])->withErrors($result['errors'] ?? []);
        } catch (Exception $e) {
            Log::error('OAuth callback error: ' . $e->getMessage());

            return redirect('/login')->withErrors(['failedAuth' => config('messages.google_auth_failed')]);
        }
    }
}
