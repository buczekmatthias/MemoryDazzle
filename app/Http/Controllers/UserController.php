<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserUpdateRequest;
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
        UserServices::handleFollow($user);

        return back();
    }

    public function usersList(Request $request)
    {
        return inertia('User/List', UserServices::getListOfUsers((string)$request->get('q', '')));
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
        UserServices::handleRequest($action, $user->id);

        return back();
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

    public function handleProfileEdit(UserUpdateRequest $userRequest)
    {
        UserServices::editProfile(
            $userRequest->except('only_delete_avatar', 'avatar'),
            $userRequest->only('only_delete_avatar')['only_delete_avatar'],
            $userRequest->only('avatar')['avatar']
        );

        return back();
    }

    public function deleteProfile()
    {
        UserServices::deleteProfile();

        return redirect()->route('security.login', status: 303);
    }
}
