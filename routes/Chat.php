<?php 

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Chat\ChatController;
use App\Http\Controllers\Chat\MessageController;





Route::middleware(['auth'])->group(function () {
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/getMessages/{id}', [MessageController::class, 'getMessages'])->name('message.getMessages');
    Route::post('/sendMessage/{id}', [MessageController::class, 'sendMessage'])->name('message.sendMessage');


});