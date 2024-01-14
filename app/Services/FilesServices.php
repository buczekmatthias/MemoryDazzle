<?php

namespace App\Services;

use App\Models\Post;
use App\Models\PostFile;
use Illuminate\Support\Facades\Storage;

class FilesServices
{
    public static function uploadFile(object $file, string $path, string $name): string
    {
        if (!Storage::directoryExists($path)) {
            Storage::makeDirectory($path);
        }

        $name = "{$name}.{$file->getClientOriginalExtension()}";

        Storage::putFileAs($path, $file, $name);

        return $name;
    }

    public static function deleteFile(array $extensions, string $path, string $name): void
    {
        foreach ($extensions as $ext) {
            if (Storage::exists("{$path}/{$name}.{$ext}")) {
                Storage::delete("{$path}/{$name}.{$ext}");
            }
        }
    }

    public static function getFilesGroupedByType(Post $post): array
    {
        $files = $post->files;

        $groupedData = [];

        foreach ($files as $file) {
            $type = explode("/", $file->mimetype)[0];

            if (in_array($type, ['image', 'video'])) {
                $groupedData[$type][] = [
                    'id' => $file->id,
                    'filename' => $file->filename
                ];
            } else {
                $groupedData['file'][] = $file->getFileContent();
            }
        }

        return $groupedData;
    }

    public static function downloadFile(PostFile $file)
    {
        return Storage::download("public/{$file->getFilePath()}");
    }
}
