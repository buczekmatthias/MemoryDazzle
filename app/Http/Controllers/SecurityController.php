<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\SecurityServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SecurityController extends Controller
{
    public function login()
    {
        return inertia('Security/Login', SecurityServices::getSecurityRoutes(true));
    }

    public function handleLogin(Request $request)
    {
        return SecurityServices::loginUser($request);
    }

    public function register()
    {
        return inertia('Security/Register', SecurityServices::getSecurityRoutes());
    }

    public function handleRegister(Request $request)
    {
        return SecurityServices::registerUser($request);
    }

    public function logout()
    {
        Auth::logout();

        return to_route('security.login');
    }

    public function resetPassword()
    {
        return inertia('Security/ResetPassword');
    }
}
