<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Services\CommentServices;

class CommentController extends Controller
{
    public function deleteComment(Comment $comment)
    {
        CommentServices::deleteComment($comment);

        return back(303);
    }

    public function createComment(CommentRequest $request)
    {
        CommentServices::createComment($request->validated());

        return back();
    }
}
