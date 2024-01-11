<?php

namespace App\Services;

use App\Models\Group;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class PostServices
{
    public static function getNewPostData(): array
    {
        return [
            'store_post_route' => route('posts.store'),
            'groups' => GroupServices::getUserGroupsList()
        ];
    }

    public static function getPostsContent(array $ids): LengthAwarePaginator
    {
        return Post::whereHas('group.owner', function ($query) use ($ids) {
            $query->whereIn('users.id', $ids);
        })
            ->select('id', 'content', 'group_id', 'created_at')
            ->with([
                'group:id,icon,name,user_id',
                'group.owner:id,displayname,username,avatar',
            ])
            ->withCount('comments')
            ->orderBy('posts.created_at', 'DESC')
            ->paginate(25)
            ->onEachSide(2)
            ->through(function ($item) {
                $itemAsArray = $item->toArray();
                $itemAsArray['files'] = FilesServices::getFilesGroupedByType($item);
                $itemAsArray['reactions'] = $item->getPostReactions();

                return $itemAsArray;
            });
    }

    public static function getHomepageFeed(): LengthAwarePaginator
    {
        return self::getPostsContent([
            auth()->user()->id,
            ...auth()->user()->following()->pluck('id')->toArray()
        ]);
    }

    public static function storePost(Request $request): RedirectResponse
    {
        $valid = $request->validate([
            'content' => 'required|string',
            'group' => 'required|uuid|exists:groups,id',
            'files' => 'nullable',
            'files.*' => 'file|max:20000'
        ]);

        if ($valid) {
            DB::transaction(function () use ($valid) {
                $group = Group::where('id', $valid['group'])->first();
                $post = $group->posts()->create(['content' => $valid['content']]);

                if ($valid['files']) {
                    foreach ($valid['files'] as $file) {
                        $post->files()->create(self::storeFiles($post->id, $file));
                    }
                }
            });

            return back();
        }

        return back()->withErrors(array_merge($valid, ['generic' => 'Posting failed']));
    }

    public static function storeFiles(string $postId, object $file): array
    {
        $userId = auth()->user()->id;
        $name = random_int(10, 800) . Carbon::now()->timestamp;

        $name = FilesServices::uploadFile($file, "public/{$userId}/{$postId}", $name);

        return [
            'filename' => $name,
            'size' => $file->getSize(),
            'mimetype' => $file->getClientMimeType()
        ];
    }
}