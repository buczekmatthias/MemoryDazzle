<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\PostServices;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function store(Request $request)
    {
        return PostServices::storePost($request);
    }

    public function view(string $post, Request $request)
    {
        return inertia('Post/View', ['post' => PostServices::getPost($post)]);
    }
}
