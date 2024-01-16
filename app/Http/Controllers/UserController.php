<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UserServices;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function profile(User $user, Request $request)
    {
        return inertia('User/Profile', UserServices::getUserData($user, $request->get('tab', 'posts')));
    }

    public function handleFollow(User $user)
    {
        return UserServices::handleFollow($user);
    }
}
