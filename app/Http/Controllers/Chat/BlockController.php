<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use App\Traits\ApiResponse;
use App\Http\Resources\BlockedUserResource;
use App\Http\Requests\UnBlockRequest;
use App\Events\BlockUser;
use App\Events\MessageSent;
use App\Events\UnBlock;
use App\Models\UserBlock;
use App\Models\User;


class BlockController extends Controller
{
    use ApiResponse;


    public function index(){
        try{
            $user=Auth::user();
            $blocks=UserBlock::where('blocker_id','=',$user->id)->get();
            return $this->successResponse(BlockedUserResource::collection($blocks),"Blocked Users",200);

        }
        catch (\Exception $e) {
            \Log::error($e->getMessage());
            return $this->errorResponse($e->getMessage());
        }
    }
    public function blockUser($user_id){
        try {
            $user=User::findorFail($user_id);
            Auth::user()->blockUser($user->id);
            BlockUser::dispatch($user,Auth::user());
            return $this->successResponse(null,"User Blocked Successfully",200);
        }
        catch (\Exception $e) {
            \Log::error($e->getMessage());
            return $this->errorResponse($e->getMessage());
        }
    }

    public function unblockUser(UnBlockRequest $request,$user_id){
        try {
            
                $user=User::findorFail($user_id);
                Auth::user()->unblockUser($user->id);
                UnBlock::dispatch($user,Auth::user());
                return $this->successResponse(null,"User Unblocked Successfully",200);
        }
        catch (\Exception $e) {
            \Log::error($e->getMessage());
            return $this->errorResponse($e->getMessage());
        }
    }
}
