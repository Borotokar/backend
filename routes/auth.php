<?php

use App\Http\Controllers\ConversationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/user')->name('user.')->group(function(){

    Route::middleware(['guest:user', 'preventBackHistory'])->group(function(){
        Route::post('/login', [UserController::class, 'login'])->name('login');
        Route::post('/otp_login', [UserController::class, 'OtpLogin'])->name('otp_login')->middleware("throttle:9,9");
	Route::get('/orderservice/{id}', [UserController::class, 'orderservice'])->name('orderservice');
	Route::get('/law', [UserController::class,'law'])->name('law');
	//Route::get('/home', [UserController::class, 'home'])->name('home');
    });

    Route::middleware(['auth:user', 'preventBackHistory'])->group(function(){
        Route::post('/login_rigster', [UserController::class, 'login_rigster']);

        Route::post('/edit', [UserController::class, 'editProfile']);
        
        Route::get('/BlockedExperts', [UserController::class, 'BlockedExperts']);
        Route::get('/me', [UserController::class, 'me']);
        Route::get('/home', [UserController::class, 'home'])->name('home');
    	Route::get('/type/{id}', [UserController::class, 'type'])->name('type');   
        Route::post('/order', [UserController::class, 'addOrder']);
        Route::get('/order', [UserController::class, 'orders']);
        Route::post('/reviews/{id}', [UserController::class, 'addreviews'])->name('addreviews');
        Route::post('/updateReview/{id}', [UserController::class, 'updateReview'])->name('updateReview');

	Route::get('/order/{id}', [UserController::class, 'order']);
	Route::get('/order/cancel/{id}', [UserController::class, 'cancelOrder']);

	Route::get('/order/conform/{OrderId}/{expertId}', [UserController::class, 'conformExpert']);
	Route::get('/order/cancelExpert/{OrderId}/{expertId}', [UserController::class, 'cancelExpert']);
	

	Route::get('/order/click/{expertId}/{serviceId}', [UserController::class, 'click']);

	Route::get('/conversations', [ConversationController::class, 'index']);
    Route::get('/conversations/{id}', [ConversationController::class, 'show']);
	Route::post('/conversations/{id}/delete', [ConversationController::class, 'deleteChat']);
	Route::post('/conversations/{id}/messages', [ConversationController::class, 'sendMessage']);
	Route::get('/seen', [ConversationController::class, 'seen']);

	Route::post('/editprofileimage', [UserController::class, 'editprofileimage'])->name('editprofileimage');
	Route::post('/set-fcm-token', [UserController::class, 'updateFcmToken'])->name('updateFcmToken');

	Route::get('/s/conversations', [UserController::class, 'suportShow']);
	Route::post('/s/conversations/{id}/messages', [UserController::class, 'suportSendMessage']);

	Route::post('/block-expert', [UserController::class, 'blockExpert']);
	Route::post('/unblock-expert', [UserController::class, 'unblockExpert']);


	Route::get('/notifs', [UserController::class, 'notifs']);

    Route::get('services', [UserController::class, 'services']);
    Route::post('/report', [ReportController::class, 'store'])->name('expertReports.store');
    });
});
