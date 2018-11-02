<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'title', 'anons', 'text','tags','image'
    ];
    public  function comments(){
        return Post::hasMany(Comment::class);
    }
}
