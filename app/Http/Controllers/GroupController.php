<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Services\GroupServices;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function view(Group $group)
    {
        return inertia('Group/View', ['group' => GroupServices::getGroupData($group)]);
    }

    public function edit(Group $group)
    {
        if ($group->id === auth()->user()->groups()->first()->id) {
            return back();
        }

        return inertia('Group/Edit', ['group' => GroupServices::getGroupEditContent($group)]);
    }

    public function handleEdit(Group $group, Request $request)
    {
        return GroupServices::updateGroup($group, $request);
    }

    public function createGroup(Request $request)
    {
        return GroupServices::createGroup($request);
    }

    public function handleDelete(Group $group)
    {
        return GroupServices::handleDeleteGroup($group);
    }
}
