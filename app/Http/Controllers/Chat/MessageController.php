<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;

use App\Models\Message;
use App\Models\Conversation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use App\Traits\ApiResponse;
use App\Http\Resources\MessageResource;
use App\Http\Resources\ConversationResource;
use Illuminate\Http\Request;
use App\Http\Requests\MessageSendRequest;
use App\Http\Requests\FindMessageRequest;
use App\Http\Requests\GetMessagesRequest;
use App\Events\BlockUser;
use App\Events\MessageSent;
use App\Notifications\NewMessageNotification;


class MessageController extends Controller
{
    use ApiResponse;
    public function getMessages(GetMessagesRequest $request,$id )
    {
        try {

            $conversation = Conversation::ConversationBetween($id)->first();
            if(!$conversation){
                return $this->successResponse(null,'no conversation yet',);
            }

           

            if(Gate::denies('chat-with', $id)){
                return $this->successResponse(new ConversationResource($conversation,false),"Conversation",200);
            }
             return $this->successResponse(new ConversationResource($conversation),"Conversation",200);
        }
        catch (\Exception $e) {
            \Log::error($e->getMessage());
            return $this->errorResponse($e->getMessage());
        }
     
        
    }

    public function sendMessage(MessageSendRequest $request,$id){
        try {
            $conversation = Conversation::conversationBetween($id)->first();
            if($conversation==null){
                $conversation = Conversation::create([
                    'type' => 'private',  
                ]);
                $conversation->users()->attach(Auth::id());
                $conversation->users()->attach($id);
            }
            $message = Message::create([
                'conversation_id' => $conversation->id,
                'user_id' => Auth::id(),
                'content' => $request->message,
            ]);
            $user=User::findorFail($id);
            MessageSent::dispatch($message,$user);
            $user->notify(new NewMessageNotification($message));
            return $this->successResponse(new MessageResource($message),"Message Sent Successfully",201);
        }
        catch (\Exception $e) {
            \Log::error($e->getMessage());
            return $this->errorResponse($e->getMessage());
        }
    }


        public function findMessage(FindMessageRequest $request){
            try {
                $res=Message::Search($request->search,$request->user_id)->get();
                if($res->isEmpty()==false){
                    return $this->successResponse(MessageResource::collection($res),"Message",200);
                }
                else{
                    return $this->successResponse(null,"No Message Found",200);
                }

            }
            catch (\Exception $e) {
                \Log::error($e->getMessage());
                return $this->errorResponse($e->getMessage());
            }
         
        }


      
}


