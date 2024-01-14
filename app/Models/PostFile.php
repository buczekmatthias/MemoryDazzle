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

    public function getFilenameAttribute()
    {
        return asset("storage/{$this->getFilePath()}");
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

    public function getFilePath()
    {
        $post = $this->post()->first();

        return "{$post->group->owner->id}/{$post->id}/{$this->getFullFileName()}";
    }

    public function getFileExtension()
    {
        return explode(".", $this->getFullFileName())[1];
    }

    public function getFileName()
    {
        return explode(".", $this->getFullFileName())[0];
    }

    public function getFullFileName()
    {
        return $this->attributes['filename'];
    }
}
