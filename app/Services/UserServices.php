<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
        FilesServices::deleteFile(['jpeg', 'png', 'svg', 'jpg'], "public/{$id}", "pfp");
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

    public static function getListOfUsers(string $q): array
    {
        return [
            'users' => User::where([
                ['username', '<>', auth()->user()->username],
                ['username', 'LIKE', "%{$q}%"]
            ])
                ->select('id', 'username', 'displayname', 'avatar', 'visibility')
                ->orderBy('username')
                ->paginate(50)
                ->through(function ($user) {
                    $userAsArray = $user->toArray();

                    $userAsArray['status'] = $user->followingStatus(auth()->user()->id);
                    $userAsArray['isPrivate'] = $user->isPrivateProfile();

                    return $userAsArray;
                }),
            'q' => $q
        ];
    }

    public static function getListOfRequests(string $tab)
    {
        /** @var User $user */
        $user = auth()->user();

        if ($tab === 'received') {
            return $user->receivedFollowRequests()->select('displayname', 'username', 'avatar')->orderBy('sent_at', 'DESC')->get();
        } else if ($tab === 'sent') {
            return $user->sentFollowRequests()->select('displayname', 'username', 'avatar')->orderBy('sent_at', 'DESC')->get();
        }
    }

    public static function handleRequest(string $action, User $user): RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user();

        if ($action === 'accept') {
            $user->followedBy()->attach($user->id);
            $user->receivedFollowRequests()->where('target_id', $user->id)->detach();
        } else if ($action === 'refuse') {
            $user->receivedFollowRequests()->where('target_id', $user->id)->detach();
        }

        return back();
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

    public static function editProfile(Request $request)
    {
        /** @var User $user */
        $user = auth()->user();

        $valid = $request->validate([
            'displayname' => 'string|nullable',
            'username' => "required|string|unique:users,username,{$user->username},username",
            'email' => "required|email|unique:users,email,{$user->email},email",
            'visibility' => 'required|string|in:private,public',
            'only_delete_avatar' => 'bool',
            'avatar' => 'file|mimetypes:image/jpeg,image/png,image/svg|max:2500|nullable'
        ]);

        if ($valid) {
            $user->displayname = $valid['displayname'];
            $user->username = $valid['username'];
            $user->email = $valid['email'];
            $user->visibility = $valid['visibility'];

            if ($valid['only_delete_avatar']) {
                self::deleteAvatar($user->id);
                $user->avatar = null;
            }

            if ($valid['avatar']) {
                $user->avatar = self::uploadAvatar($valid['avatar'], $user->id);
            }

            $user->save();

            return back();
        }

        return back()->withErrors($valid);
    }

    public static function deleteProfile()
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

        return redirect()->route('security.login', status: 303);
    }
}
