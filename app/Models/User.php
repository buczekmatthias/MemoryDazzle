<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'displayname',
        'username',
        'email',
        'password',
        'avatar',
        'visibility'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    public function following()
    {
        return $this->belongsToMany(User::class, 'user_following', 'follower_id', 'user_id');
    }

    public function followedBy()
    {
        return $this->belongsToMany(User::class, 'user_following', 'user_id', 'follower_id');
    }

    public function sentFollowRequests()
    {
        return $this->belongsToMany(User::class, 'follow_requests', 'target_id', 'sender_id');
    }

    public function receivedFollowRequests()
    {
        return $this->belongsToMany(User::class, 'follow_requests', 'sender_id', 'target_id');
    }

    public function groups()
    {
        return $this->hasMany(Group::class);
    }

    public function posts()
    {
        return $this->hasManyThrough(Post::class, Group::class);
    }

    public function reactions()
    {
        return $this->belongsToMany(Post::class, 'user_post_reactions', 'post_id', 'user_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function isPrivateProfile(): bool
    {
        return $this->attributes['visibility'] === 'private';
    }

    public function getAvatarAttribute()
    {
        return asset($this->attributes['avatar']);
    }
}
