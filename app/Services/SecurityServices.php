<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\UploadedFile;
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

    public static function registerUser(array $data, ?UploadedFile $avatar): void
    {
        $user = User::create($data);
        $user->avatar = UserServices::uploadAvatar($avatar, $user->id, false);
        $user->groups()->create(['name' => 'General', 'icon' => '💾']);

        $user->save();
    }
}
