<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Pagination\LengthAwarePaginator;

class CommentServices
{
    public static function deleteComment(Comment $comment): void
    {
        if (auth()->user()->id === $comment->author->id) {
            $comment->delete();
        }
    }

    public static function createComment(array $data): void
    {
        $comment = Post::where('id', $data['post_id'])->first()->comments()->create(['content' => $data['content']]);
        $comment->author()->associate(auth()->user())->save();
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
