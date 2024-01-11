<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class SecurityServices
{
    public static function getSecurityRoutes($withPasswordReset = false): array
    {
        $res = [
            'login_route' => route('security.login'),
            'register_route' => route('security.register'),
            'current_route' => route(Route::currentRouteName())
        ];

        if ($withPasswordReset) {
            $res['reset_password'] = route('security.resetPassword');
        }

        return $res;
    }

    public static function loginUser(Request $request): RedirectResponse
    {
        $valid = $request->validate([
            'username' => 'required|string|exists:users,username',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);

        if ($valid) {
            if (Auth::attempt($request->only(['username', 'password']), $valid['remember_me'])) {
                return to_route('homepage');
            }

            return to_route('security.login')->withErrors(['username' => 'Invalid credentials', 'password' => 'Invalid credentials']);
        }

        return to_route('security.login')->withErrors($valid);
    }

    public static function registerUser(Request $request): RedirectResponse
    {
        $valid = $request->validate([
            'displayname' => 'string|nullable',
            'username' => 'required|string|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string',
            'avatar' => 'file|mimetypes:image/jpeg,image/png,image/svg|max:2500|nullable'
        ]);

        if ($valid) {
            $user = User::create($request->except('avatar'));
            $user->avatar = UserServices::uploadAvatar($valid['avatar'], $user->id, false);
            $user->groups()->create(['name' => 'General', 'icon' => '💾']);

            $user->save();

            return to_route('security.login');
        }

        return to_route('security.register')->withErrors($valid);
    }
}
