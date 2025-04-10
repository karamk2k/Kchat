<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Conversation;
Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('MessageForUser.{id}', function ($user, $id) {
    // Log::info("Checking access for user: {$user->id} to MessageForUser.{$id}");
    
 
    return (int) $user->id === (int) $id;
});

Broadcast::channel('ChatForUser.{id}', function ($user, $id) {
    try {
        
        if ($id!=null) {
           
            $id = Conversation::with('users')->find($id);

            
            if ($id && $id->users->isNotEmpty()) {
              
                $userIn = $id->users->contains(function ($userObj) use ($user) {
                    return $userObj->id === $user->id;
                });
                \Log::info("User: {$user->id} is in conversation: {$id->id}");
                
                return $userIn; 
            } else {
              
                Log::warning("No users found for conversation ID {$id}");
                return false;
            }
        }

       
        Log::warning("No conversation ID provided for subscription.");
        return false;

    } catch (\Exception $e) {
      
        Log::error("Error in channel authorization: " . $e->getMessage());
        return false;
    }
});


