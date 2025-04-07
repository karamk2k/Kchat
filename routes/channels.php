<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('MessageForUser.{id}', function ($user, $id) {
    Log::info("Checking access for user: {$user->id} to MessageForUser.{$id}");
    
 
    return (int) $user->id === (int) $id;
});