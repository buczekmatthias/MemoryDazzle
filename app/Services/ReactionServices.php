<?php

namespace App\Services;

use App\Models\Post;
use App\Models\Reaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReactionServices
{
    public static function addReaction(Request $request): RedirectResponse
    {
        $reaction = Post::where('id', $request->post('post_id'))->first()->reactions()->create($request->only(['reaction', 'reaction_name']));
        $reaction->user()->associate(auth()->user())->save();

        return back();
    }

    public static function removeReaction(string $postId, string $reactionName): RedirectResponse
    {
        Reaction::where([['post_id', $postId], ['user_id', auth()->user()->id], ['reaction_name', $reactionName]])->delete();

        return back(303);
    }
}
