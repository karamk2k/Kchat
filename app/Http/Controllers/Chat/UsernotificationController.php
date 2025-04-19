<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserNotification;
use App\Http\Requests\MarkAsReadRequest;

use App\Traits\ApiResponse;
class UsernotificationController extends Controller
{
    use ApiResponse;
   public function index() {
    try {
        return $this->successResponse( UserNotification::collection(Auth::user()->unreadNotifications),'User Notification',200);
  
    } catch (\Exception $e) {
        \Log::error($e->getMessage());
        return $this->errorResponse($e->getMessage());
    }
      
   }


/**
 * Marks notifications as read for the authenticated user.
 *
 * Filters the unread notifications of the authenticated user based on the 
 * 'from_user_id' provided in the request, and marks the filtered notifications as read.
 *
 * @param MarkAsReadRequest $request
 * @return \Illuminate\Http\JsonResponse
 */


   public function markAsRead(MarkAsReadRequest $request) {
    try {
      Auth::user()->readNotifications($request->from_user_id);
        return $this->successResponse([],'User Notification',200);
    } catch (\Exception $e) {
        \Log::error($e->getMessage());
        return $this->errorResponse($e->getMessage());
    }
      
   }
}
