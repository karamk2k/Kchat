<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;



class ConversationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

     public function __construct($resource,public  $can_send_message=true) 
     {
        parent::__construct($resource);
     }
    public function toArray(Request $request): array
    {

        return [
            'id' => $this->id,
            'type' => $this->type,
            'name' => $this->name,
            'created_at' => $this->created_at,
            'messages' => MessageResource::collection($this->messages),
            'can_send_message' => $this->can_send_message

        ];
    }
}
