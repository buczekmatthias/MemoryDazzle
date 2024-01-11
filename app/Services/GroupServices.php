<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;

class GroupServices
{
    public static function getUserGroupsList(): Collection
    {
        return auth()->user()->groups()->select('id', 'icon', 'name')->get();
    }
}
