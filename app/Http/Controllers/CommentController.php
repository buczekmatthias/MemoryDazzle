<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Services\CommentServices;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function deleteComment(Comment $comment)
    {
        return CommentServices::deleteComment($comment);
    }

    public function createComment(Request $request)
    {
        return CommentServices::createComment($request);
    }
}
