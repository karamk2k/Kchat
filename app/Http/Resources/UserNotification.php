<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserNotification extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'type'=>$this->type,
            'message_id'=>$this->data['message_id'],
            'from_user'=>$this->data['from_user'],
            'from_user_id'=>$this->data['from_user_id'],
            'message'=>$this->data['text'],
            'read_at'=>$this->read_at,
            'created_at'=>formatNotificationTime($this->created_at)

        ];
    }
}
