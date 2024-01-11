<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UserServices;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function profile(User $user)
    {
        return inertia('User/Profile', UserServices::getUserData($user));
    }
}
