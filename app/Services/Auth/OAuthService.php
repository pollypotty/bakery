<?php

namespace App\Services\Auth;

use App\Enums\SocialEnum;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;

class OAuthService
{
    public function handleGoogleCallback($authAction): array
    {
        try {
            $user = Socialite::driver('google')->user();
            $authUser = DB::table('users')->where('email', $user->email)->first();

            if ($authAction === 'register') {
                if ($authUser) {
                    return [
                        'status' => 'error',
                        'redirect' => '/registration',
                        'errors' => ['registeredEmail' => config('messages.registered_email')],
                    ];
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

                Auth::loginUsingId($userId);
                return [
                    'status' => 'success',
                    'redirect' => '/profile',
                ];
            }

            if ($authAction === 'login') {
                if (!$authUser) {
                    return [
                        'status' => 'error',
                        'redirect' => '/login',
                        'errors' => ['notFound' => config('messages.user_not_found')],
                    ];
                }

                Auth::loginUsingId($authUser->id);
                return [
                    'status' => 'success',
                    'redirect' => '/profile',
                ];
            }

            return [
                'status' => 'error',
                'redirect' => '/login',
                'errors' => ['invalidState', config('messages.invalid_state')],
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'redirect' => '/login',
                'errors' => ['failedAuth', $e->getMessage()],
            ];
        }
    }
}
