<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UserServices
{
    public static function getUserData(User $user, string $tab): array
    {
        $currentUserId = auth()->user()->id;

        $isUserFollowedByCurrentUser = $user->followedBy()->where('follower_id', $currentUserId)->exists();

        $hasAccess = $currentUserId === $user->id || !$user->isPrivateProfile() || $isUserFollowedByCurrentUser;

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

        $userData = $user->where('username', $user->username)
            ->select('displayname', 'username', 'avatar', 'created_at');

        if ($hasAccess) {
            $userData->withCount('comments', 'posts', 'following', 'followedBy', 'groups');
        } else {
            $userData->withCount('following', 'followedBy');
        }

        return            [
            'tab' => $tab,
            'hasAccess' => $hasAccess,
            'profile' => array_merge(
                $userData->first()->toArray(),
                [
                    'isPrivate' => $user->isPrivateProfile()
                ]
            ),
            'content' => $content,
            'status' => $isUserFollowedByCurrentUser ? 'Following' : $user->followingStatus($currentUserId)
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
        FilesServices::deleteFile(['jpeg', 'png', 'svg', 'jpg'], "public/{$id}", "pfp");
    }

    public static function handleFollow(User $user): void
    {
        $currentUserId = auth()->user()->id;

        if ($user->followedBy()->where('follower_id', $currentUserId)->exists()) {
            $user->followedBy()->detach($currentUserId);
        } else if ($user->receivedFollowRequests()->where('sender_id', $currentUserId)->exists()) {
            $user->receivedFollowRequests()->detach($currentUserId);
        } else {
            if ($user->isPrivateProfile()) {
                $user->receivedFollowRequests()->attach($currentUserId);
            } else {
                $user->followedBy()->attach($currentUserId);
            }
        }
    }

    public static function getListOfUsers(string $q): array
    {
        return [
            'users' => User::where([
                ['username', '<>', auth()->user()->username],
                ['username', 'LIKE', "%{$q}%"]
            ])
                ->select('id', 'username', 'displayname', 'avatar', 'visibility')
                ->withCount([
                    'followedBy' => fn ($q) => $q->where('follower_id', auth()->user()->id),
                    'following' => fn ($q) => $q->where('user_id', auth()->user()->id),
                    'receivedFollowRequests' => fn ($q) => $q->where('sender_id', auth()->user()->id)
                ])
                ->orderBy('username')
                ->paginate(50)
                ->through(function ($user) {
                    $userAsArray = $user->toArray();

                    $userAsArray['isPrivate'] = $user->isPrivateProfile();

                    return $userAsArray;
                }),
            'q' => $q
        ];
    }

    public static function getListOfRequests(string $tab): Collection
    {
        /** @var User $user */
        $user = auth()->user();

        if ($tab === 'received') {
            return $user->receivedFollowRequests()->select('id', 'displayname', 'username', 'avatar')->orderBy('sent_at', 'DESC')->get();
        } else if ($tab === 'sent') {
            return $user->sentFollowRequests()->select('id', 'displayname', 'username', 'avatar')->orderBy('sent_at', 'DESC')->get();
        }
    }

    public static function handleRequest(string $action, string $userId): void
    {
        auth()->user()->receivedFollowRequests()->detach($userId);

        if ($action === 'accept') {
            auth()->user()->followedBy()->attach($userId);
        }
    }

    public static function getFollowingList(User $user, string $tab): array
    {
        $list = [
            'tab' => $tab,
            'user' => $user->username,
            'users' => []
        ];

        if ($tab === 'followers') {
            $list['users'] = $user->followedBy()->orderBy('started_at', 'DESC')->select('displayname', 'username', 'avatar')->paginate(50);
        } else if ($tab === 'following') {
            $list['users'] = $user->following()->orderBy('started_at', 'DESC')->select('id', 'username', 'displayname', 'avatar', 'visibility')->paginate(50)->through(function ($user) {
                $userAsArray = $user->toArray();

                $userAsArray['status'] = $user->followingStatus($user->id);
                $userAsArray['isPrivate'] = $user->isPrivateProfile();

                return $userAsArray;
            });
        }

        return $list;
    }

    public static function editProfile(array $data, bool $only_delete_avatar, ?UploadedFile $avatar): void
    {
        /** @var User $user */
        $user = auth()->user();

        $user->update($data);

        if ($only_delete_avatar) {
            self::deleteAvatar($user->id);
            $user->avatar = null;
        }

        if ($avatar) {
            $user->avatar = self::uploadAvatar($avatar, $user->id);
        }

        $user->save();
    }

    public static function deleteProfile(): void
    {
        /** @var User $user */
        $user = auth()->user();

        DB::transaction(function () use ($user) {
            $user->following()->detach();
            $user->followedBy()->detach();
            $user->sentFollowRequests()->detach();
            $user->receivedFollowRequests()->detach();
            foreach ($user->groups as $group) {
                GroupServices::deleteGroup($group);
            }
            $user->reactions()->delete();
            $user->comments()->delete();

            if ($user->avatar) {
                self::deleteAvatar($user->id);
            }

            if (Storage::directoryExists('public/' . $user->id)) {
                Storage::deleteDirectory("public/" . $user->id);
            }

            $user->delete();
        });
    }
}
