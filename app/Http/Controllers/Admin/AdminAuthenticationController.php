<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminLoginRequest;
use App\Http\Requests\LoginRequest;
use App\Services\Admin\AdminAuthenticationService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;

class AdminAuthenticationController extends Controller
{
    protected AdminAuthenticationService $adminAuthenticationService;

    public function __construct(AdminAuthenticationService $adminAuthenticationService)
    {
        $this->adminAuthenticationService = $adminAuthenticationService;
    }

    public function index(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin.admin_login');
    }

    public function login(AdminLoginRequest $request): RedirectResponse
    {
        $requestData = $request->all();

        if ($this->adminAuthenticationService->login($requestData)) {
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['email' => config('messages.invalid_admin_credentials')]);
    }

    public function logout(): RedirectResponse
    {
       $this->adminAuthenticationService->logout();

       return redirect()->route('admin.login');
    }
}
