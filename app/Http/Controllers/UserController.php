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

    public function usersList()
    {
        return inertia('User/List', UserServices::getListOfUsers());
    }

    public function listFollowRequests(Request $request)
    {
        return inertia('User/Requests', [
            'tab' => $request->get('tab', 'received'),
            'requests' => UserServices::getListOfRequests($request->get('tab', 'received'))
        ]);
    }

    public function handleFollowRequests(string $action, User $user)
    {
        return UserServices::handleRequest($action, $user);
    }

    public function followers(User $user, Request $request)
    {
        return inertia('User/Followers', [
            'list' => UserServices::getFollowingList($user, $request->get('tab', 'following'))
        ]);
    }

    public function profileEdit()
    {
        return inertia('User/Edit', [
            'profile' => auth()->user()->only('displayname', 'username', 'email', 'avatar', 'visibility')
        ]);
    }

    public function handleProfileEdit(Request $request)
    {
        return UserServices::editProfile($request);
    }

    public function deleteProfile()
    {
        return UserServices::deleteProfile();
    }
}
