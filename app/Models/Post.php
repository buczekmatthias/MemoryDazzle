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

    public function author()
    {
        return $this->group()->owner();
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function reactions()
    {
        return $this->hasMany(Reaction::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function files()
    {
        return $this->hasMany(PostFile::class);
    }

    public function getCreatedAtAttribute()
    {
        return \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $this->attributes['created_at'])->format('M d, Y H:i');
    }

    public function getPostReactions()
    {
        return $this->reactions()
            ->selectRaw(
                'reaction, reaction_name, COUNT(user) as reaction_count, CASE WHEN MAX(CASE WHEN user_id = ? THEN 1 ELSE 0 END) = 1 THEN TRUE ELSE FALSE END AS user_reacted',
                [auth()->user()->id]
            )
            ->groupBy('reaction', 'reaction_name')
            ->get()
            ->toArray();
    }
}
