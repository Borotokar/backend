<?php

use App\Http\Controllers\AdminController;
use App\Models\Admin;
use App\Models\Service;
use App\Models\Servicecategory;
use App\Models\ServiceType;
use App\Models\User;
use App\Models\expert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Models\UserAppSetting;
//use App\Models\ServiceType;
//use App\Models\Servicecategory;
//use App\Models\Service;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// use App\Services\ExpertFirebaseNotificationService;

// Route::get('/send-notif', function () {
// 	$expert = expert::find(1);



	    // if (!is_null($expert->fcm_token)) {
		// 	$token = $expert->fcm_token;
		// 	$fcm = new ExpertFirebaseNotificationService();
		// 	$fcm->send(
		// 		$token,
		// 		'تست نوتیف',
		// 		'این یک پیام تستی از سمت سروره 😎',
		// 		['customData' => '123']
		// 	);
        // }

//     return '✅ ارسال شد';
// });

Route::get('/', function(){
	return response()->json(
		[
		  "message"=>"Hi Im  developr of this project and my email : borotokardev@gmail.com "
		],
	200);
})->middleware("throttle:10,2");

Route::get('/app_version', function(){
	return response()->json(
		[
			 "latest_version"=> "1.0.0", "force_update"=> false, "message"=> "این یک تست جهت رویت از طرف احسان گل است!" 
		],
	200);
})->middleware("throttle:10,2");

Route::get('/app_version2', function(){
	return response()->json(
		[
			 "latest_version"=> "1.0.0", "force_update"=> false, "message"=> "این یک تست جهت رویت از طرف احسان گل است!" 
		],
	200);
})->middleware("throttle:10,2");



