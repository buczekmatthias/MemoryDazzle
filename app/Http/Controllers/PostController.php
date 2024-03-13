<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostCreateRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Models\Post;
use App\Services\GroupServices;
use App\Services\PostServices;

class PostController extends Controller
{
    public function store(PostCreateRequest $request)
    {
        PostServices::storePost($request->validated());

        return back();
    }

    public function view(string $post)
    {
        return inertia('Post/View', [
            'post' => PostServices::getPost($post)
        ]);
    }

    public function delete(Post $post)
    {
        PostServices::deletePost($post);

        return redirect()->route('homepage', status: 303);
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

    public function handleEdit(Post $post, PostUpdateRequest $request)
    {
        if (!$post || $post->author()->id !== auth()->user()->id) {
            return back();
        }

        PostServices::updatePost($post, $request->validated());

        return back();
    }
}
