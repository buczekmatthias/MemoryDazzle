<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'icon',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
