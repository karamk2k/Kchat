<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ChatController extends Controller
{
    public function index()
    {
        $users=User::where('id','!=',Auth::user()->id)->get();
        return view('Chat.ChatRoom',compact('users'));
    }
}
