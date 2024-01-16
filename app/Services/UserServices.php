<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\RedirectResponse;

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
            'status' => $user->followingStatus($currentUserId),
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

    public static function getListOfUsers(): array
    {
        return [
            'users' => User::where('username', '<>', auth()->user()->username)->select('id', 'username', 'displayname', 'avatar', 'visibility')->orderBy('username')->paginate(50)->through(function ($user) {
                $userAsArray = $user->toArray();

                $userAsArray['status'] = $user->followingStatus(auth()->user()->id);
                $userAsArray['isPrivate'] = $user->isPrivateProfile();

                return $userAsArray;
            })
        ];
    }

    public static function getListOfRequests(string $tab)
    {
        if ($tab === 'received') {
            return auth()->user()->receivedFollowRequests()->select('displayname', 'username', 'avatar')->orderBy('sent_at', 'DESC')->get();
        } else if ($tab === 'sent') {
            return auth()->user()->sentFollowRequests()->select('displayname', 'username', 'avatar')->orderBy('sent_at', 'DESC')->get();
        }
    }

    public static function handleRequest(string $action, User $user): RedirectResponse
    {
        if ($action === 'accept') {
            auth()->user()->followedBy()->attach($user->id);
            auth()->user()->receivedFollowRequests()->where('target_id', $user->id)->detach();
        } else if ($action === 'refuse') {
            auth()->user()->receivedFollowRequests()->where('target_id', $user->id)->detach();
        }

        return back();
    }
}
