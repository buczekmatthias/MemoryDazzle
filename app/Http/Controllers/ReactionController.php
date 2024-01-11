<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ReactionServices;
use Illuminate\Http\Request;

class ReactionController extends Controller
{
    public function addReaction(Request $request)
    {
        return ReactionServices::addReaction($request);
    }

    public function removeReaction(string $post_id, string $reaction_name)
    {
        return ReactionServices::removeReaction($post_id, $reaction_name);
    }
}
