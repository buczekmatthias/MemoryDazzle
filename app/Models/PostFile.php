<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'filename',
        'size',
        'mimetype'
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function getSizeAttribute()
    {
        return round($this->attributes['size'] / 1000000, 2);
    }

    public function getFileContent()
    {
        $filename = explode(".", $this->getFullFileName());

        return [
            'id' => $this->attributes['id'],
            'filename' => $filename[0],
            'extension' => $filename[1]
        ];
    }

    public function getFilePath(string $userId, string $postId)
    {
        return asset("storage/{$userId}/{$postId}/{$this->attributes['filename']}");
    }

    public function getFileExtension()
    {
        return explode(".", $this->getFullFileName())[1];
    }

    public function getFileName()
    {
        return explode(".", $this->getFullFileName())[0];
    }
}
