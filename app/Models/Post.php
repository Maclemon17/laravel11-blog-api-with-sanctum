<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    protected $fillable = [
        'title',
        'content',
        'user_id',
    ];

    function Author(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id');
    }

    function Comment(): BelongsTo {
        return $this->belongsTo(Comment::class, 'user_id');
    }


    function Likes(): BelongsTo {
        return $this->belongsTo(Like::class, 'user_id');
    }


}
