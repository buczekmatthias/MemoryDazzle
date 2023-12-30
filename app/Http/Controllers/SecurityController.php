<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\UserServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SecurityController extends Controller
{
    public function login()
    {
        return inertia('Security/Login', UserServices::getSecurityRoutes(true));
    }

    public function handleLogin(Request $request)
    {
        return UserServices::loginUser($request);
    }

    public function register()
    {
        return inertia('Security/Register', UserServices::getSecurityRoutes());
    }

    public function handleRegister(Request $request)
    {
        return UserServices::registerUser($request);
    }

    public function logout()
    {
        Auth::logout();

        return to_route('security.login');
    }

    public function resetPassword()
    {
        //TODO: Develop feature 
        return inertia('Security/ResetPassword');
    }
}
