<?php

namespace App\Http\Controllers;

use App\Enums\SocialEnum;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse;

class OAuthController extends Controller
{
    public function redirectToGoogleLogin(): RedirectResponse|\Illuminate\Http\RedirectResponse
    {
        session(['auth_action' => 'login']);
        return Socialite::driver('google')->redirect();
    }

    public function redirectToGoogleRegister(): RedirectResponse|\Illuminate\Http\RedirectResponse
    {
        session(['auth_action' => 'register']);
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->user();
            $authUser = DB::table('users')->where('email', $user->email)->first();

            $authAction = session('auth_action');

            if ($authAction === 'register') {
                if ($authUser) {
                    return redirect('/registration')->withErrors(['registeredEmail' => config('messages.registered_email')]);

                }

                $userId = DB::table('users')->insertGetId([
                    'email' => $user->email,
                    'first_name' => $user->user['given_name'],
                    'last_name' => $user->user['family_name'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                DB::table('social_media_auths')->insert([
                    'id' => $user->id,
                    'user_id' => $userId,
                    'provider' => SocialEnum::GOOGLE->value,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $authUser = DB::table('users')->where('id', $userId)->first();
                Auth::loginUsingId($authUser->id);
                return redirect('profile');
            }

            if ($authAction === 'login') {
                if (!$authUser) {
                    return redirect('/login')->withErrors(['notFound' => config('messages.user_not_found')]);
                }

                Auth::loginUsingId($authUser->id);
                return redirect('profile');
            }

            return redirect('/login')->withErrors(['invalidState', config('messages.invalid_state')]);

        } catch (\Exception $e) {
            return redirect('/login')->withErrors(['failedAuth', config('messages.google_auth_failed')]);
        }
    }
}
