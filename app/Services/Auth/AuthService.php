<?php

namespace App\Services\Auth;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthService
{
    /**
     * @throws Exception
     */
    public function login(array $requestData): bool
    {
        try {
            if (Auth::attempt([
                'email' => $requestData['email'],
                'password' => $requestData['password'],
            ])) {
                return true;
            }

            return false;
        } catch (Exception $e) {
            Log::error('Login attempt failed.', ['exception' => $e]);
            throw new Exception(config('messages.login_custom_error'));
        }
    }

    public function logout(Request $request): void
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
}
