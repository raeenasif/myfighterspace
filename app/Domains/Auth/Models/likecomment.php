<?php

namespace App\Domains\Auth\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class likecomment extends Model
{
    use HasFactory;


    protected $fillable = [
        'user_id',
        'comment_id',
        'value',
    ];

    public function getUser()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
