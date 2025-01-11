<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
     /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

     protected $fillable = [
        'post_id',
        'user_id',
    ];

    
}
