<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function reactions()
    {
        return $this->belongsToMany(User::class, 'user_post_reactions', 'user_id', 'post_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function files()
    {
        return $this->hasMany(PostFile::class);
    }
}
