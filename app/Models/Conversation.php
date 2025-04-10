<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Conversation extends Model
{
    use SoftDeletes,HasFactory;

    protected $fillable = [
        'type',
        'name',
        'created_by',
       
    ];
//Relations

    public function users() {
        return $this->belongsToMany(User::class, 'conversation_room');
    }

    public function messages() {
        return $this->hasMany(Message::class);
    }
    public function scopeConversationBetween($query, $user1, $user2 = null)
    {
        $user2 = $user2 ?? auth()->id();
        
        return $query->whereHas('users', function($q) use ($user1) {
                $q->where('users.id', $user1);
            })
            ->whereHas('users', function($q) use ($user2) {
                $q->where('users.id', $user2);
            })
            ->withCount('users')
            ->having('users_count', 2);
    }
    
}
