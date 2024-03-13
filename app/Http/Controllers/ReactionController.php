<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReactionRequest;
use App\Models\Post;
use App\Models\Reaction;
use App\Services\ReactionServices;

class ReactionController extends Controller
{
    public function addReaction(Post $post, ReactionRequest $request)
    {
        ReactionServices::addReaction($post, $request->validated());

        return back();
    }

    public function removeReaction(string $post_id, string $reaction_name)
    {
        Reaction::where([['post_id', $post_id], ['user_id', auth()->user()->id], ['reaction_name', $reaction_name]])->delete();

        return back(303);
    }
}
