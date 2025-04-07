<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Message extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'conversation_id',
        'user_id',
        'content',
    ];
//Relations

    public function conversation() {
        return $this->belongsTo(Conversation::class);
    }
    public function user() {
        return $this->belongsTo(User::class);
    }

    
}
