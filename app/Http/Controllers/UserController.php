<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $request): Application|RedirectResponse|Redirector|\Illuminate\Foundation\Application
    {
        $request->validate([
            'email'     => 'required|email|max:60|unique:users',
            'firstName' => 'required|string|max:40|regex:/^[a-zA-Z\s-]+$/',
            'lastName'  => 'required|string|max:40|regex:/^[a-zA-Z\s-]+$/',
            'password'  => 'required|min:6|confirmed',
        ]);

        $requestData = $request->all();

        $user = new User();

        $user->first_name = $requestData['firstName'];
        $user->last_name = $requestData['lastName'];
        $user->email = $requestData['email'];
        $user->password = Hash::make($requestData['password']);

        if ($user->save()) {
            Auth::login($user);
            return redirect('profile');
        }

        return redirect()->back()->withErrors(['message' => config('messages.registration_error')]);
    }

    public function login(Request $request): \Illuminate\Foundation\Application|Redirector|RedirectResponse|Application
    {
        $request->validate([
            'email'     => 'required|email|max:60',
            'password'  => 'required|min:6',
        ]);

        $requestData = $request->all();

        if (Auth::attempt([
            'email' => $requestData['email'],
            'password' => $requestData['password'],
        ])) {
            return redirect('profile');
        }

        return redirect()->back()->withErrors(['email' => config('messages.login_error')]);
    }

    public function logout(Request $request): JsonResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([], 204);
    }
}
