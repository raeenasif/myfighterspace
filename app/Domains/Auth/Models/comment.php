<?php

namespace App\Domains\Auth\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'post_id',
        'comment',

    ];

    public function getUser()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function CommentlikeCount()
    {
        return $this->HasMany(likecomment::class)->where('value', 1);
    }
}
