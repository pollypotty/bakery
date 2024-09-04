<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Services\Auth\AuthService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function index(): Factory|\Illuminate\Foundation\Application|View|Application
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request): \Illuminate\Foundation\Application|Redirector|RedirectResponse|Application
    {
        $requestData = $request->all();

        try {
            if ($this->authService->login($requestData)) {
                return redirect('profile');
            }

            return redirect()->back()->withErrors(['login_err' => config('messages.login_error')]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['message' => $e->getMessage()]);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout($request);

        return response()->json([], 204);
    }
}
