<?php

namespace App\Domains\Auth\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class follow extends Model
{
    use HasFactory;
    protected $fillable = [
        'from_user',
        'to_user',
        '	request',
    ];

    public function getUser()
    {
        return $this->belongsTo(User::class, 'from_user', 'id');
    }
    public function getUserFollowing()
    {
        return $this->belongsTo(User::class, 'to_user', 'id');
    }
}
