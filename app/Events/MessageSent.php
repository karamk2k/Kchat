<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Models\Message;
use App\Models\User;
use App\Http\Resources\MessageResource;
class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public Message $message,public User $user)
    {
        //
        \Log::info('MessageSent Event Dispatched', ['message' => $message->content, 'userId' => $user->id]);

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('MessageForUser.'.$this->user->id),
        ];
    }
    public function broadcastAs()
    {
        return 'MessageSent';
    }
    public function broadcastWith()
    {
        return [
            'message' => new MessageResource($this->message),
            'user' => $this->user
        ];
    }
}
