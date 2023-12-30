<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

class UserServices
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

    public static function loginUser(Request $request)
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

    public static function registerUser(Request $request)
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
            $user->avatar = self::uploadAvatar($valid['avatar'], $user->id, true, false);
            $user->groups()->create(['name' => 'General']);

            $user->save();

            return to_route('security.login');
        }

        return to_route('security.register')->withErrors($valid);
    }

    public static function uploadAvatar(?object $file, string $id, bool $createFolder = false, bool $deleteOldAvatar = true): string|null
    {
        if (!$file) {
            return null;
        }

        if ($createFolder && !Storage::directoryExists("public/{$id}")) {
            Storage::makeDirectory("public/{$id}");
        }

        if ($deleteOldAvatar) {
            self::deleteAvatar($id);
        }

        $name = "pfp." . $file->getClientOriginalExtension();

        Storage::putFileAs("public/{$id}", $file, $name);

        return "storage/{$id}/{$name}";
    }

    public static function deleteAvatar(string $id)
    {
        foreach (['jpeg', 'png', 'svg'] as $ext) {
            if (Storage::exists("public/{$id}/pfp.{$ext}")) {
                Storage::delete("public/{$id}/pfp.{$ext}");
            }
        }
    }
}
