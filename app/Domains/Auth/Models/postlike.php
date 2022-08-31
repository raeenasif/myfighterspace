<?php

namespace App\Domains\Auth\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class postlike extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'post_id',
        'value',
    ];

    public function getUser()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
