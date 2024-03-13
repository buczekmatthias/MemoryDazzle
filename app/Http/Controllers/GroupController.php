<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\GroupRequest;
use App\Models\Group;
use App\Services\GroupServices;

class GroupController extends Controller
{
    public function view(Group $group)
    {
        return inertia('Group/View', [
            'group' => GroupServices::getGroupData($group)
        ]);
    }

    public function edit(Group $group)
    {
        if ($group->id === auth()->user()->groups()->first()->id) {
            return back();
        }

        return inertia('Group/Edit', [
            'group' => $group->only('id', 'name', 'icon')
        ]);
    }

    public function handleEdit(Group $group, GroupRequest $request)
    {
        $group->update($request->validated());

        return back();
    }

    public function createGroup(GroupRequest $request)
    {
        auth()->user()->groups()->create($request->validated());

        return back();
    }

    public function handleDelete(Group $group)
    {
        GroupServices::deleteGroup($group);

        return redirect()->route('user.profile', [
            'user' => auth()->user()->username,
            'tab' => 'groups'
        ], 303);
    }
}
