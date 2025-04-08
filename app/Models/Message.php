<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Conversation;


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

    public function scopeSearch($query, $search,$user_id) {
        $conv_id=optional(Conversation::ConversationBetween($user_id)->first())->id;
        if($conv_id){
            return $query->where('conversation_id', $conv_id)->where('content', 'like', '%'.$search.'%');
        }

        return ;
    }

    
}
