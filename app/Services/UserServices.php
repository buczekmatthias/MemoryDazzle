<?php

namespace App\Services;

use App\Models\User;

class UserServices
{
    public static function getUserData(User $user, string $tab): array
    {
        $currentUserId = auth()->user()->id;

        $hasAccess = !($user->isPrivateProfile() && !$user->followedBy->contains($currentUserId) && $currentUserId !== $user->id);

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
            'status' => $user->followedBy->contains($currentUserId) ? 'following' : ($user->receivedFollowRequests->contains($currentUserId) ? 'pending' : 'follow'),
            'profile' => $user
                ->where('username', $user->username)
                ->select('displayname', 'username', 'avatar', 'created_at')
                ->withCount('comments', 'posts', 'following', 'followedBy', 'groups')
                ->first()
                ->toArray(),
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
}
