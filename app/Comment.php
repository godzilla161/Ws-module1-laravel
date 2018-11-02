<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'author', 'comment', 'post_id',
    ];

    public function post(){
        return Comment::belongsTo(Post::class);
    }
}
