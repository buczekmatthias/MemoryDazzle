<?php

namespace App\Services;

use App\Models\Group;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class PostServices
{
    public static function getNewPostData(): array
    {
        return [
            'store_post_route' => route('posts.store'),
            'groups' => GroupServices::getUserGroupsList(auth()->user()->id)
        ];
    }

    public static function getPost(string $postId): array|null
    {
        $post = Post::where('id', $postId)
            ->select('id', 'content', 'group_id', 'created_at')
            ->with([
                'group:id,icon,name,user_id',
                'group.owner:id,displayname,username,avatar',
            ])
            ->first();

        if (!$post) {
            return null;
        }

        $postAsArray = $post->toArray();

        $postAsArray['files'] = FilesServices::getFilesGroupedByType($post);
        $postAsArray['reactions'] = ReactionServices::getPostReactions($post);
        $postAsArray['comments'] = CommentServices::getPostComments($post->id);

        return $postAsArray;
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
            ->withCount('comments', 'files', 'reactions')
            ->orderBy('posts.created_at', 'DESC')
            ->paginate(25);
    }

    public static function getGroupPosts(string $group_id): LengthAwarePaginator
    {
        return Post::where('group_id', $group_id)
            ->select('id', 'content', 'group_id', 'created_at')
            ->withCount('comments', 'files', 'reactions')
            ->orderBy('posts.created_at', 'DESC')
            ->paginate(25);
    }

    public static function getPostEditContent(string $post_id): array
    {
        $post = Post::where('id', $post_id)
            ->select('id', 'content', 'group_id')
            ->first();

        $postAsArray = $post->toArray();

        $postAsArray['files'] = FilesServices::getFilesList($post);

        return $postAsArray;
    }

    public static function getHomepageFeed(): LengthAwarePaginator
    {
        return self::getPostsContent([
            auth()->user()->id,
            ...auth()->user()->following()->pluck('id')->toArray()
        ]);
    }

    public static function storePost(array $data): void
    {
        DB::transaction(function () use ($data) {
            $group = Group::where('id', $data['group'])->first();
            $post = $group->posts()->create(['content' => $data['content']]);

            if ($data['files']) {
                foreach ($data['files'] as $file) {
                    $post->files()->create(self::storeFiles($post->id, $file));
                }
            }
        });
    }

    public static function deletePost(Post $post): void
    {
        if (auth()->user()->id === $post->author()->id) {
            FilesServices::postDeleteFiles("public/" . auth()->user()->id . "/{$post->id}");
            $post->files()->delete();
            $post->reactions()->delete();
            $post->comments()->delete();
            $post->delete();
        }
    }

    public static function updatePost(Post $post, array $data): void
    {
        $post->content = $data['content'];
        $post->group()->associate(Group::where('id', $data['group_id'])->first());

        if (sizeof($post->files) !== $data['files']) {
            $newIds = array_column($data['files'], 'id');
            foreach ($post->files as $file) {
                if (!in_array($file->id, $newIds)) {
                    FilesServices::deleteFile([$file->getFileExtension()], "public/" . auth()->user()->id . "/{$post->id}", $file->getFileName());
                    $post->files()->where('id', $file->id)->delete();
                }
            }
        }

        $post->save();
    }

    public static function storeFiles(string $postId, object $file): array
    {
        $userId = auth()->user()->id;
        $name = random_int(10, 800) . Carbon::now()->timestamp;

        $name = FilesServices::uploadFile($file, "public/{$userId}/{$postId}", $name);

        return [
            'filename' => $name,
            'size' => $file->getSize(),
            'mimetype' => $file->getClientMimeType(),
        ];
    }
}
