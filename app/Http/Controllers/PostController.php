<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Services\GroupServices;
use App\Services\PostServices;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function store(Request $request)
    {
        return PostServices::storePost($request);
    }

    public function view(string $post)
    {
        return inertia('Post/View', ['post' => PostServices::getPost($post)]);
    }

    public function delete(Post $post)
    {
        return PostServices::deletePost($post);
    }

    public function edit(Post $post)
    {
        if (!$post || $post->author()->id !== auth()->user()->id) {
            return back();
        }

        return inertia('Post/Edit', [
            'post' => PostServices::getPostEditContent($post->id),
            'groups' => GroupServices::getUserGroupsList(auth()->user()->id)
        ]);
    }

    public function handleEdit(Post $post, Request $request)
    {
        if (!$post || $post->author()->id !== auth()->user()->id) {
            return back();
        }

        return PostServices::updatePost($post, $request);
    }
}
