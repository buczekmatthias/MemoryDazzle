<?php

namespace App\Services;

use App\Models\Group;
use Illuminate\Database\Eloquent\Collection;
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

    public static function deleteGroup(Group $group)
    {
        DB::transaction(function () use ($group) {
            foreach ($group->posts as $post) {
                PostServices::deletePost($post);
            }

            $group->delete();
        });
    }
}
