<?php

namespace App\Services\Auth;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class RegistrationService
{
    /**
     * @throws Exception
     */
    public function register(array $requestData): User
    {
        try {
            $user = new User();

            $user->first_name = $requestData['firstName'];
            $user->last_name = $requestData['lastName'];
            $user->email = $requestData['email'];
            $user->password = Hash::make($requestData['password']);
            $user->save();

            Auth::login($user);

            return $user;
        } catch (Exception $e) {
            Log::error(config('messages.registration_log_error'), ['exception' => $e->getMessage()]);
            throw new Exception(config('messages.registration_error'));
        }
    }
}
