<?php 

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Chat\ChatController;
use App\Http\Controllers\Chat\MessageController;
use App\Http\Controllers\Chat\BlockController;
use App\Http\Controllers\Chat\UsernotificationController;





Route::middleware(['auth'])->group(function () {
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/getMessages/{id}', [MessageController::class, 'getMessages'])->name('message.getMessages');
    Route::post('/sendMessage/{user_id}', [MessageController::class, 'sendMessage'])->name('message.sendMessage')->middleware('can:chat-with,user_id');
    Route::post('/blockUser/{user_id}', [BlockController::class, 'blockUser'])->name('message.blockUser')->middleware('can:chat-with,user_id');
    Route::get('/findMessage', [MessageController::class, 'findMessage'])->name('message.findMessage');
    Route::get('/blocked_users', [BlockController::class, 'index'])->name('block.index');
    Route::delete('/unblockUser/{user_id}', [BlockController::class, 'unblockUser'])->name('block.unblockUser');
    Route::get('/UserNotification', [UsernotificationController::class, 'index'])->name('User.UserNotification');
    Route::put('/markAsRead/{from_user_id}', [UsernotificationController::class, 'markAsRead'])->name('User.markAsRead');

});