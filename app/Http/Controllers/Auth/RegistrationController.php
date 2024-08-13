<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Services\Auth\RegistrationService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;

class RegistrationController extends Controller
{
    protected RegistrationService $registrationService;

    public function __construct(RegistrationService $registrationService)
    {
        $this->registrationService = $registrationService;
    }

    public function index(): Factory|\Illuminate\Foundation\Application|View|Application
    {
        return view('register');
    }

    public function register(RegisterRequest $request): Application|RedirectResponse|Redirector|\Illuminate\Foundation\Application
    {
        $requestData = $request->all();

        try {
            $this->registrationService->register($requestData);
            return redirect('profile');
        } catch (\Exception $e) {
             return redirect('/registration')->withErrors(['message' => $e->getMessage()]);
        }
    }
}
