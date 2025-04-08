<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;

use App\Models\Message;
use App\Models\Conversation;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use App\Traits\ApiResponse;
use App\Http\Resources\MessageResource;
use App\Http\Resources\ConversationResource;
use Illuminate\Http\Request;
use App\Http\Requests\MessageSendRequest;
use App\Http\Requests\FindMessageRequest;
use App\Events\MessageSent;


class MessageController extends Controller
{
    use ApiResponse;
    public function getMessages($id )
    {
        try {
            $conversation = Conversation::ConversationBetween($id)->first();
            if(!$conversation){
                return $this->successResponse(null,'no conversation yet',);
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
                $conversation->Users()->attach(Auth::id());
                $conversation->Users()->attach($id);
            }
            $message = Message::create([
                'conversation_id' => $conversation->id,
                'user_id' => Auth::id(),
                'content' => $request->message,
            ]);
            $user=User::findorFail($id);
            MessageSent::dispatch($message,$user);
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


