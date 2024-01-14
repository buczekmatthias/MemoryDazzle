<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CommentServices
{
    public static function deleteComment(Comment $comment): RedirectResponse
    {
        if (auth()->user()->id === $comment->author->id) {
            $comment->delete();
        }

        return back(303);
    }

    public static function createComment(Request $request)
    {
        $valid = $request->validate([
            'content' => 'required|string',
            'post_id' => 'required|uuid|exists:posts,id'
        ]);

        if ($valid) {
            $comment = Post::where('id', $valid['post_id'])->first()->comments()->create(['content' => $valid['content']]);
            $comment->author()->associate(auth()->user())->save();

            return back();
        }

        return back()->withErrors($valid);
    }
}
