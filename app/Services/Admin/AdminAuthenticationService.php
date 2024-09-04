<?php

namespace App\Services\Admin;

class AdminAuthenticationService
{
    public function login($requestData): bool
    {
        if ($requestData['admin_email'] === env('ADMIN_EMAIL') && $requestData['admin_password'] === env('ADMIN_PASSWORD')) {
            session()->put('admin_logged_in', true);

            return true;
        }

        return false;
    }

    public function logout(): void
    {
        session()->forget(('admin_logged_in'));
    }
}
