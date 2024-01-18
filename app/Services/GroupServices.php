<?php

namespace App\Services;

use App\Models\Group;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GroupServices
{
    public static function getUserGroupsList(string $user_id, bool $withCount = false): Collection
    {
        $groups = Group::where('user_id', $user_id)->select('id', 'icon', 'name', 'user_id');

        if ($withCount) {
            $groups->with('owner:id,username')->withCount('posts');
        }

        return $groups->get();
    }

    public static function getGroupData(Group $group): array
    {
        $group = $group->where('id', $group->id)
            ->select('id', 'name', 'icon', 'user_id')
            ->with('owner:id,displayname,username')
            ->first();

        $groupAsArray = $group->toArray();

        $groupAsArray['posts'] = PostServices::getGroupPosts($group->id);

        return $groupAsArray;
    }

    public static function getGroupEditContent(Group $group): array
    {
        return $group->only('id', 'name', 'icon');
    }

    public static function updateGroup(Group $group, Request $request)
    {
        $valid = $request->validate([
            'icon' => 'required|string',
            'name' => 'required|string'
        ]);

        if ($valid) {
            $group->update([
                'icon' => $valid['icon'],
                'name' => $valid['name']
            ]);
        }

        return back();
    }

    public static function createGroup(Request $request)
    {
        $valid = $request->validate([
            'icon' => 'required|string',
            'name' => 'required|string'
        ]);

        if ($valid) {
            /** @var User $user */
            $user = auth()->user();

            $user->groups()->create([
                'icon' => $valid['icon'],
                'name' => $valid['name']
            ]);
        }

        return back();
    }

    public static function deleteGroup(Group $group)
    {
        DB::transaction(function () use ($group) {
            foreach ($group->posts as $post) {
                PostServices::deletePost($post);
            }

            $group->delete();
        });
    }

    public static function handleDeleteGroup(Group $group)
    {
        self::deleteGroup($group);

        return redirect()->route('user.profile', ['user' => auth()->user()->username, 'tab' => 'groups'], 303);
    }
}
