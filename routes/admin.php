<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserReportController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProposalTypeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SupportChatController;
// header('Access-Control-Allow-Methods:  POST, GET, OPTIONS, PUT, DELETE');

Route::prefix('admin')->name('admin.')->group(function(){

    Route::middleware(['guest:admin', 'preventBackHistory'])->group(function(){
        Route::view('/login', 'page.login')->name('login');
        Route::view('/forget_password', 'page.forgetpassword')->name('forget_password');
        Route::post('/login_handller', [AdminController::class, 'login_handller'])->name('login_handller')->middleware("throttle:10,2");
    });

    Route::middleware(['auth:admin', 'preventBackHistory'])->group(function(){
        // Route::view('/dashbord', 'page.admin')->name('home');
        Route::get('/dashbord', [AdminController::class, 'index'])->name('home');
        Route::get('/editprofile', [AdminController::class, 'editprofile'])->name('editprofile');
        Route::get('/logout_handller', [AdminController::class, 'logout_handller'])->name('logout_handller');
        Route::post('/editprofilehandler', [AdminController::class, 'editprofilehandler'])->name('editprofilehandler');

        // service routes
        Route::get('/services', [AdminController::class, 'services'])->name('services')->middleware(['role:Service_manager']);
        Route::post('/delleteservice/{id}', [AdminController::class, 'deleteService'])->name('delleteservice')->middleware(['role:Service_manager']);
        Route::get('/editservice/{id}', [AdminController::class, 'editService'])->name('editservice')->middleware(['role:Service_manager']);
        Route::post('/updateService/{id}', [AdminController::class, 'updateService'])->name('updateService')->middleware(['role:Service_manager']);
        Route::post('/addservicehandller', [AdminController::class, 'addservicehandller'])->name('addservicehandller')->middleware(['role:Service_manager']);
	Route::get('/services/search', [AdminController::class, 'search'])->name('services.search');
        Route::patch('/services/{id}/toggle-status', [AdminController::class, 'toggleStatus'])
         ->name('services.toggle-status');


        // categouris routes
        Route::get('/categoris', [AdminController::class, 'categoris'])->name('categoris')->middleware(['role:categoris_manager']);
        Route::post('/deletecategoris/{id}', [AdminController::class, 'deletecategoris'])->name('deletecategoris')->middleware(['role:categoris_manager']);
        Route::get('/editcategoris/{id}', [AdminController::class, 'editcategoris'])->name('editcategoris')->middleware(['role:categoris_manager']);
        Route::post('/updatecategoris/{id}', [AdminController::class, 'updatecategoris'])->name('updatecategoris')->middleware(['role:categoris_manager']);
        Route::post('/addcategoris', [AdminController::class, 'addcategoris'])->name('addcategoris')->middleware(['role:categoris_manager']);
	Route::get('/cats/search', [AdminController::class, 'searchCats'])->name('cats.search');
        
        // types routes
        Route::get('/types', [AdminController::class, 'types'])->name('types')->middleware(['role:type_manager']);
        Route::post('/deletetypes/{id}', [AdminController::class, 'deletetypes'])->name('deletetypes')->middleware(['role:type_manager']);
        Route::get('/edittypes/{id}', [AdminController::class, 'edittypes'])->name('edittypes')->middleware(['role:type_manager']);
        Route::post('/updatetypes/{id}', [AdminController::class, 'updatetypes'])->name('updatetypes')->middleware(['role:type_manager']);
        Route::post('/addtypes', [AdminController::class, 'addtypes'])->name('addtypes')->middleware(['role:type_manager']);
	Route::get('/types/search', [AdminController::class, 'searchType'])->name('types.search');

        // user routes
        Route::get('/users', [AdminController::class, 'users'])->name('users')->middleware(['role:User_manager']);
        Route::get('/userappsetting', [AdminController::class, 'userappsetting'])->name('userappsetting')->middleware(['role:user_app_setting_manager']);
        Route::post('/userappsettingupdate', [AdminController::class, 'userappsettingupdate'])->name('userappsettingupdate')->middleware(['role:user_app_setting_manager']);
        Route::get('/useredit/{id}', [AdminController::class, 'useredit'])->name('useredit')->middleware(['role:User_manager']);
        Route::post('/activedeactiveuser/{id}', [AdminController::class, 'activedeactiveuser'])->name('activedeactiveuser')->middleware(['role:User_manager']);
        Route::post('/userupdate/{id}', [AdminController::class, 'userupdate'])->name('userupdate')->middleware(['role:User_manager']);
	Route::get('/users/search', [AdminController::class, 'searchUser'])->name('user.search');
	
	// experts routes 
        Route::get('/experts', [AdminController::class, 'experts'])->name('experts')->middleware(['role:Expert_manager']);
        Route::get('/expertsBlue', [AdminController::class, 'expertBlueTickRequest'])->name('expertsBlue')->middleware(['role:Expert_manager']);
        Route::post('/expertsBlue/{id}', [AdminController::class, 'setExpertBlueTick'])->name('setExpertsBlue')->middleware(['role:Expert_manager']);
        Route::post('/expertsBlue/d/{id}', [AdminController::class, 'unsetExpertBlueTick'])->name('unsetExpertsBlue')->middleware(['role:Expert_manager']);
        
        Route::get('/expertsaccesslist', [AdminController::class, 'expertsaccesslist'])->name('expertsaccesslist')->middleware(['role:expert_allow_list_manager']);
 	Route::get('/expertRejectList', [AdminController::class, 'expertRejectList'])->name('expertRejectList')->middleware(['role:expert_allow_list_manager']);

	Route::get('/editexpert/{id}', [AdminController::class, 'editexpert'])->name('editexpert')->middleware(['role:Expert_manager']);
        Route::get('/expertsaccess/{id}', [AdminController::class, 'expertsaccess'])->name('expertsaccess');
        Route::post('/addexpert', [AdminController::class, 'addexpert'])->name('addexpert')->middleware(['role:Expert_manager']);
        Route::post('/upadetexpert/{id}', [AdminController::class, 'upadetexpert'])->name('upadetexpert')->middleware(['role:Expert_manager']);
        Route::post('/activedeactiveexpert/{id}', [AdminController::class, 'activedeactiveexpert'])->name('activedeactiveexpert');
        Route::post('/expertaccesshandller/{id}', [AdminController::class, 'expertaccesshandller'])->name('expertaccesshandller');
	Route::get('/expert/search', [AdminController::class, 'searchExpert'])->name('expert.search');
        Route::get('/expertappsetting', [AdminController::class, 'expertappsetting'])->name('expertappsetting')->middleware(['role:expert_app_manager']);
        Route::post('/expertappsettingupdate', [AdminController::class, 'expertappsettingupdate'])->name('expertappsettingupdate');
       	Route::get('/experts/search', [AdminController::class, 'searchExpertN'])->name('expertn.search'); 
	Route::get('/wallet/search', [AdminController::class, 'searchWallet'])->name('wallet.search');
	// orders routes
        Route::get('/orders', [AdminController::class, 'orders'])->name('orders')->middleware(['role:Orders_manager']);
        Route::get('/transactions', [AdminController::class, 'transactions'])->name('transactions')->middleware(['role:transactions_manager']);
	Route::get('/transaction/search', [AdminController::class, 'searchTransactions'])->name('transaction.search');
	Route::get('/order/{id}', [AdminController::class, 'order'])->name('order')->middleware(['role:Orders_manager']);
        Route::post('/deleteorder/{id}', [AdminController::class, 'deleteorder'])->name('deleteorder')->middleware(['role:Orders_manager']);
	Route::get('/orders/search', [AdminController::class, 'searchOrder'])->name('order.search'); 
        // reviews routes
        Route::get('/reviews', [AdminController::class, 'reviews'])->name('reviews')->middleware(['role:comments_manager']);
        Route::post('/accessreview/{id}', [AdminController::class, 'accessreview'])->name('accessreview')->middleware(['role:comments_manager']);
        Route::post('/deletereview/{id}', [AdminController::class, 'deletereview'])->name('deletereview')->middleware(['role:comments_manager']);
	Route::get('/review/search', [AdminController::class, 'searchReviews'])->name('review.search');

	Route::get('/notif', [AdminController::class, 'notifications'])->name('notif')->middleware(['role:Notif_manager']);
	Route::post('/notif', [AdminController::class, 'addnotifications'])->name('addnotif');
	Route::delete('/notif/{id}', [AdminController::class, 'deletenotifications'])->name('deletenotif');

        Route::get('/enotif', [AdminController::class, 'enotifications'])->name('enotif')->middleware(['role:Notif_manager']);
	Route::post('/enotif', [AdminController::class, 'eaddnotifications'])->name('eaddnotif');
	Route::delete('/enotif/{id}', [AdminController::class, 'edeletenotifications'])->name('edeletenotif');
        
	Route::get('/msg', [AdminController::class, 'userSendMessage'])->name('msg')->middleware(['role:Notif_manager']);
	Route::post('/msgh', [AdminController::class, 'userSendMessage_handller'])->name('msgh');
	Route::get('/emsg', [AdminController::class, 'expertSendMessage'])->name('emsg')->middleware(['role:Notif_manager']);
	Route::post('/emsgh', [AdminController::class, 'expertSendMessage_handller'])->name('emsgh');

	Route::get('/proposal_types', [ProposalTypeController::class, 'index'])->name('proposal_types.index')->middleware(['role:propesal_type_manager']);
	Route::get('/proposal_types/create', [ProposalTypeController::class, 'create'])->name('proposal_types.create');
	Route::post('/proposal_types', [ProposalTypeController::class, 'store'])->name('proposal_types.store');
	Route::get('/proposal_types/{id}/edit', [ProposalTypeController::class, 'edit'])->name('proposal_types.edit');
	Route::post('/proposal_types/{id}', [ProposalTypeController::class, 'update'])->name('proposal_types.update');
	Route::delete('/proposal_types/{id}', [ProposalTypeController::class, 'destroy'])->name('proposal_types.destroy');

	// wallets routes
        Route::get('/wallets', [AdminController::class, 'wallets'])->name('wallets')->middleware(['role:wallet_manager']);
        Route::get('/walletEdit/{id}', [AdminController::class, 'walletEdit'])->name('walletEdit');
        Route::post('/walletEditHandller/{id}', [AdminController::class, 'walletEditHandller'])->name('walletEditHandller');

	Route::get('/reports', [ReportController::class, 'index'])->name('expertReports');
    Route::patch('/reports/{id}/{status}', [ReportController::class, 'updateStatus'])->name('expertReports.updateStatus');	
	// admins  routes
        Route::get('/admins', [AdminController::class, 'admins'])->name('admins')->middleware(['role:general_manager']);
        Route::get('/admins/create', [AdminController::class, 'createAdmin'])->name('create')->middleware(['role:general_manager']);
        Route::post('/admins', [AdminController::class, 'storeAdmin'])->name('store')->middleware(['role:general_manager']);

        
        Route::get('/adminEdit/{id}', [AdminController::class, 'editAdmin'])->name('editAdmin')->middleware(['role:general_manager']);
	Route::post('/adminEditHandller/{id}', [AdminController::class, 'editAdmin_handller'])->name('editAdmin_handller')->middleware(['role:general_manager']);
	Route::post('/AdminDelete/{id}', [AdminController::class, 'AdminDelete'])->name('AdminDelete')->middleware(['role:general_manager']);	
    	Route::get('/chats/{id}', [AdminController::class, 'showChats'])->name('chats.show');
	Route::get('/chats', [AdminController::class, 'chats'])->name('chats.index');
    	Route::get('/user-reports', [UserReportController::class, 'index'])->name('user-reports');
	Route::patch('/user-reports/{id}', [UserReportController::class, 'updateStatus'])->name('user-reports.update');
    	Route::get('/reports/{id}/{status}', [ReportController::class, 'updateStatus'])->name('expertReports.eupdateStatus');

        // Suport 
        Route::get('support/user', [SupportChatController::class, 'uindex'])->name('support.uindex');
        Route::get('support/expert', [SupportChatController::class, 'eindex'])->name('support.eindex');
        Route::get('support/{id}', [SupportChatController::class, 'show'])->name('support.show');
        Route::post('support/message', [SupportChatController::class, 'sendMessage'])->name('support.send');
    });

        Route::get('/notifications', [AdminController::class, 'nindex'])->name('notifications.index');
    	Route::post('/notifications/{id}/read', [AdminController::class, 'markAsRead'])->name('notifications.markAsRead');
	
});
