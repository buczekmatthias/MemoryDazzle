<?php

namespace App\Services;

use App\Models\Group;
use Illuminate\Database\Eloquent\Collection;

class GroupServices
{
    public static function getUserGroupsList(string $user_id, bool $withCount = false): Collection
    {
        $groups = Group::where('user_id', $user_id)->select('id', 'icon', 'name');

        if ($withCount) {
            $groups->withCount('posts');
        }

        return $groups->get();
    }
}
