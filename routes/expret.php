<?php

use App\Http\Controllers\ExpertController;
use App\Models\expert;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\UserReportController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

Route::prefix('api/expert')->name('expert.')->group(function(){

    Route::middleware(['guest:expert', 'preventBackHistory'])->group(function(){
        Route::post('/login', [ExpertController::class, 'login'])->name('login')->middleware("throttle:100,2");
        Route::post('/otp_login', [ExpertController::class, 'OtpLogin'])->name('OtpLogin')->middleware("throttle:100,2");
        Route::post('/otp_rigister', [ExpertController::class, 'otp_rigister'])->name('otp_rigister')->middleware("throttle:100,2");
        Route::post('/rigister', [ExpertController::class, 'rigister'])->name('rigister')->middleware("throttle:100,2");
        Route::post('wallet/topup/callback', [ExpertController::class, 'topUpCallback'])->name('callback');
	Route::get('wallet/topup', [ExpertController::class, 'topUp']);
	Route::get('/law', [ExpertController::class, 'law'])->name('law')->middleware("throttle:100,2");

    });

    Route::middleware(['auth:expert', 'preventBackHistory'])->group(function(){
        Route::get('/order', [ExpertController::class, 'order'])->name('order')->middleware("throttle:1000,2");
	Route::get('/delete_gallery/{id}', [ExpertController::class, 'deleteImage'])->name('deleteImage')->middleware("throttle:100,2");
        Route::get('/me', [ExpertController::class, 'me'])->name('me')->middleware("throttle:100,2");

	Route::post('/report', [UserReportController::class, 'store'])->name('report');
        Route::post('/edit_expert', [ExpertController::class, 'edit_expert'])->name('edit_expert')->middleware("throttle:100,2");
        Route::post('/upload', [ExpertController::class, 'upload'])->name('upload')->middleware("throttle:100,2");
        Route::post('/editprofileimage', [ExpertController::class, 'editprofileimage'])->name('editprofileimage')->middleware("throttle:100,2");
        Route::post('/editgallery', [ExpertController::class, 'editgallery'])->name('editgallery')->middleware("throttle:100,2");
        Route::post('/editdocuments', [ExpertController::class, 'editdocuments'])->name('editdocuments')->middleware("throttle:100,2");
        Route::post('/ebtd', [ExpertController::class, 'editBlueTickDocuments'])->name('editBlueTickDocuments')->middleware("throttle:100,2");
        Route::post('/sendbids/{id}', [ExpertController::class, 'sendbids'])->name('sendbids')->middleware("throttle:100,2");
        Route::get('services', [ExpertController::class, 'services']);
	Route::post('/conversations/{id}/delete', [ConversationController::class, 'deleteChat']); 
        Route::get('/conversations', [ConversationController::class, 'eindex']);
        Route::get('/conversations/{id}', [ConversationController::class, 'eshow']);
        Route::post('/conversations/{id}/messages', [ConversationController::class, 'esendMessage']);
        Route::get('/seen', [ConversationController::class, 'eseen']);	

        Route::get('/s/conversations', [ExpertController::class, 'suportShow']);
	Route::post('/s/conversations/{id}/messages', [ExpertController::class, 'suportSendMessage']);

        Route::post('/block-user', [ExpertController::class, 'blockUser']);
	Route::post('/unblock-user', [ExpertController::class, 'unblockUser']);
        Route::get('/BlockedUsers', [ExpertController::class, 'BlockedUsers']);
        
	Route::post('/reviewreq/{id}', [ExpertController::class, 'reviewRequest']);

	Route::get('/notifs', [ExpertController::class, 'notifs']);
	
        Route::post('set-fcm-token', [ExpertController::class, 'updateFcmToken'])->name('updateFcmToken');
    });
});
