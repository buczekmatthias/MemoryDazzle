<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

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

    public static function getPostComments(string $post_id): LengthAwarePaginator
    {
        return Comment::where('post_id', $post_id)
            ->select('id', 'content', 'created_at', 'user_id')
            ->with('author:id,displayname,username,avatar')
            ->orderBy('comments.created_at', 'DESC')
            ->paginate(20);
    }

    public static function getUserComments(string $user_id): LengthAwarePaginator
    {
        return Comment::where('user_id', $user_id)
            ->select('id', 'content', 'created_at', 'user_id', 'post_id')
            ->with('author:id,displayname,username,avatar')
            ->orderBy('created_at', 'DESC')
            ->paginate(20);
    }
}
