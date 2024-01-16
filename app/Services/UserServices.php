<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\RedirectResponse;

class UserServices
{
    public static function getUserData(User $user, string $tab): array
    {
        $currentUserId = auth()->user()->id;

        $following = $user->followedBy->contains($currentUserId);

        $hasAccess = !($user->isPrivateProfile() && !$following && $currentUserId !== $user->id);

        $content = null;

        if ($hasAccess) {
            if ($tab === 'posts') {
                $content = PostServices::getPostsContent([$user->id]);
            } else if ($tab === 'comments') {
                $content = CommentServices::getUserComments($user->id);
            } else if ($tab === 'groups') {
                $content = GroupServices::getUserGroupsList($user->id, true);
            }
        }

        return [
            'tab' => $tab,
            'hasAccess' => $hasAccess,
            'status' => $following ? 'following' : ($user->receivedFollowRequests->contains($currentUserId) ? 'pending' : 'follow'),
            'profile' => array_merge(
                $user
                    ->where('username', $user->username)
                    ->select('displayname', 'username', 'avatar', 'created_at')
                    ->withCount('comments', 'posts', 'following', 'followedBy', 'groups')
                    ->first()
                    ->toArray(),
                [
                    'isPrivate' => $user->isPrivateProfile()
                ]
            ),
            'content' => $content,
        ];
    }

    public static function uploadAvatar(?object $file, string $id, bool $deleteOldAvatar = true): string|null
    {
        if (!$file) {
            return null;
        }

        if ($deleteOldAvatar) {
            self::deleteAvatar($id);
        }

        $name = FilesServices::uploadFile($file, "public/{$id}", "pfp");

        return "storage/{$id}/{$name}";
    }

    public static function deleteAvatar(string $id): void
    {
        FilesServices::deleteFile(['jpeg', 'png', 'svg'], "public/{$id}", "pfp");
    }

    public static function handleFollow(User $user): RedirectResponse
    {
        $currentUserId = auth()->user()->id;

        if ($user->followedBy->contains($currentUserId)) {
            $user->followedBy()->where('follower_id', $currentUserId)->detach();
        } else if ($user->receivedFollowRequests->contains($currentUserId)) {
            $user->receivedFollowRequests()->where('target_id', $currentUserId)->detach();
        } else {
            if ($user->isPrivateProfile()) {
                $user->receivedFollowRequests()->attach($currentUserId);
            } else {
                $user->followedBy()->attach($currentUserId);
            }
        }

        return back();
    }
}
