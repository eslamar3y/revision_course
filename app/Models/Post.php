<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'title',
        'body',
        'auther_id'
    ];

    public function auther()
    {
        return $this->belongsTo(User::class, 'auther_id');
    }
}
