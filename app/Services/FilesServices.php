<?php

namespace App\Services;

use App\Models\Post;
use App\Models\PostFile;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

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

    public static function postDeleteFiles(string $path): void
    {
        $files = Storage::allFiles($path);
        Storage::delete($files);
        Storage::deleteDirectory($path);
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
                    'path' => $file->getFilePath($post->author()->id, $post->id)
                ];
            } else {
                $groupedData['file'][] = $file->getFileContent();
            }
        }

        return $groupedData;
    }

    public static function getFilesList(Post $post): array
    {
        $files = $post->files;

        $list = [];

        foreach ($files as $file) {
            $type = explode("/", $file->mimetype)[0];

            $list[] = [
                'id' => $file->id,
                'type' => in_array($type, ['image', 'video']) ? $type : 'file',
                'filename' => $file->filename,
                'name' => $file->getFullFileName()
            ];
        }

        return $list;
    }

    public static function downloadFile(PostFile $file): StreamedResponse
    {
        $path = "public/{$file->post->author()->id}/{$file->post->id}/{$file->filename}";

        return Storage::download($path);
    }
}
