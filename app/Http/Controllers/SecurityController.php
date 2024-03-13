<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\SecurityServices;
use Illuminate\Support\Facades\Auth;

class SecurityController extends Controller
{
    public function login()
    {
        return inertia('Security/Login', SecurityServices::getSecurityRoutes(true));
    }

    public function handleLogin(LoginRequest $request)
    {
        if (Auth::attempt(
            $request->only(['username', 'password']),
            $request->post('remember_me')
        )) {
            return to_route('homepage');
        }

        return to_route('security.login')->withErrors(['username' => 'Invalid credentials', 'password' => 'Invalid credentials']);
    }

    public function register()
    {
        return inertia('Security/Register', SecurityServices::getSecurityRoutes());
    }

    public function handleRegister(RegisterRequest $request)
    {
        SecurityServices::registerUser($request->except('avatar'), $request->only('avatar')['avatar']);

        return to_route('security.login');
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
