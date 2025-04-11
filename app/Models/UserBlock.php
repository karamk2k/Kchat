<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserBlock extends Model
{
    protected $table = 'user_blocks';

    protected $fillable = [
        'blocker_id',
        'blocked_id',
    ];

    public function blockedUser()
        {
            return $this->belongsTo(User::class, 'blocked_id');
        }
}
