<?php

namespace App\Services;

use App\Models\User;

class UserServices
{
    public static function getUserData(User $user): array
    {
        return [
            'user' => array_merge($user->only(['displayname', 'username', 'avatar']), ['isPrivate' => $user->isPrivateProfile()])
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
