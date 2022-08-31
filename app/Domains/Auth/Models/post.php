<?php

namespace App\Domains\Auth\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Domains\Auth\Models\PostLike;

class post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'post',
        'user_id'
    ];


    public function likeCount()
    {
        return $this->HasMany(PostLike::class)->where('value', 1);
    }

    public function CommentCount()
    {
        return $this->HasMany(comment::class);
    }
}
