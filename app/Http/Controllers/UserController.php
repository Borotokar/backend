<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Order;
use App\Models\Review;
use App\Models\Service;
use App\Models\Servicecategory;
use App\Models\ServiceType;
use App\Models\SupportMessages;
use App\Models\Conversation;
use App\Models\SupportConversations;
use App\Models\User;
use App\Models\Bid;
use App\Models\Wallet;
use App\Models\expert;
use Illuminate\Support\Facades\DB;
use App\Models\Blocking;
use App\Models\UserAppSetting;
use App\Models\UserNotification;
use App\Models\VerificationCode;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use IPPanel\Client;
use Kavenegar;
use Kavenegar\KavenegarApi;
use Kavenegar\Laravel\Message\KavenegarMessage;
use Illuminate\Support\Facades\File as FacadesFile;
use App\Jobs\SendSmsNotification;
use App\Jobs\NotifyNearbyExperts;
use App\Services\ExpertFirebaseNotificationService;

class UserController extends Controller
{
    //
    public function me(Request $request){
        $user = Auth::user();

        return response()->json([
            'user'=> $user
        ], 200);
    }

    public function login_rigster(Request $request){

        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'sex' => 'required',
        ],
        [
            'sex.required' => 'sex is required',
            'name.required' => 'name is required',
        ]);

        $userDetails = Auth::user();  // To get the logged-in user details
        $user = User::find($userDetails ->id);
        $user->name = $request->first_name."  ".$request->last_name;
        $user->sex = $request->sex;
        $user->save();

        return response()->json([
            'msg' => "اطلاعات با موفقیت ثبت شد",
            "user" => $user
            ], 200);;


    }

    public function login(Request $request){

    $request->validate([
        'login_id' => 'required',
    ],
    [
        'login_id.required' => 'Phone or Username is required',
        'login_id.exists' => 'Phone or Username isnot required',
    ]);

    //geneate otp
    $verificationCode = VerificationCode::where('phone', $request->login_id)->latest()->first();

    $now = Carbon::now();

    if($verificationCode && $now->isBefore($verificationCode->expire_at)){
        return response()->json([
            'login_id'=> $request->login_id,
            'msg' => "یک کد برای شما ارسال شده لطفا بعد از دو دقیقه تلاش کنید"
            ], 200);;
    }

    $code = VerificationCode::create([
        'phone' => $request->login_id,
        'otp' => rand(12345, 99999),
        'expire_at' => Carbon::now()->addMinutes(1),
        'ip' => $request->ip()
    ]);
    // // todo send otp sms

    try{
    //$api = new KavenegarApi( "445A693566472F757349713846345544735933486F6A59506E4E415775374B5632684E415956584B464B413D" );
    $res = array($request->login_id);

    $receptor = $request->login_id;
    //$token = $code->otp;
    //<#> کد فعال سازی حساب کاربری: %token
    // بروتوکار | borotokar.com
    $message = "<#> کد فعال سازی حساب کاربری\ncode: ".$code->otp."\nبروتوکار | borotokar.com\nCXnKU8Qs0Jm";
    $result = Kavenegar::Send("9982001368", $res, $message);   

    //$template="borotokar-account-verification";
    //$result = $api->VerifyLookup($receptor, $token, $token2, $token3, $template, $type = null);
    }
    catch(\Kavenegar\Exceptions\ApiException $e){
        // در صورتی که خروجی وب سرویس 200 نباشد این خطا رخ می دهد
        $erore = $e->errorMessage();
        return response()->json([
            'msg' => $erore
            ], 200);
    }

    catch(\Kavenegar\Exceptions\HttpException $e){
        // در زمانی که مشکلی در برقرای ارتباط با وب سرویس وجود داشته باشد این خطا رخ می دهد
        $erore = $e->errorMessage();
        return response()->json([
            'msg' => $erore
            ], 200);
    }


    return response()->json([
        'login_id'=> $request->login_id,
        'ip'=> $request->ip(),
        // "otp"=>$code->otp,
        'msg' => "کد شما با موفقیت ارسال شد ."
        ], 200);


    }

    public function OtpLogin(Request $request){

        // validate phone num and otp
        $request->validate([
            'login_id' => 'required',
            'otp' => 'required|digits:5'
        ],
        [
            'login_id.required' => 'Phone or Username is required',
            'login_id.exists' => 'Phone or Username isnot required',
        ]);
        // if user with this datils exist send tlogin token
        $verificationCode   = VerificationCode::where('phone', $request->login_id)->where('otp', $request->otp)->first();

        $now = Carbon::now();

        if (!$verificationCode) {
            return response()->json([
                'msg'=> "کد وارد شده اشتباه می باشد"
                ], 200);
        }
        elseif($verificationCode && $now->isAfter($verificationCode->expire_at)){
            $verificationCode->delete();

            return response()->json([
                'msg'=> "مدت اعتبار کد شما به پایان رسیده لطفا دوباره اقدام کنید"
                ], 200);
        }
        elseif($verificationCode->ip != $request->ip()){
            return response()->json([
                'msg'=> "شما اجازه ورود با این ip را ندارید"
                ], 503);
        }
        // else send  user is not regstered and get user ditalis
        $user = User::where('phone_number', $request->login_id)->first();

        if (!$user) {
            $create_user = User::create([
                'phone_number' => $request->login_id,
		'username' => "user$request->login_id",
		"picture" => "img/default.png"
            ]);
            $token = Auth::guard('user')->login($create_user, true);
            $verificationCode->delete();
            return response()->json([
                'login_id'=> $request->login_id,
                'status' => 'rigster',
                'token' => $token
                ], 200);
        }

        $token = Auth::guard('user')->login($user, true);
        $verificationCode->delete();
        if ($user->name == null) {
            return response()->json([
                'login_id'=> $request->login_id,
                'status' => 'rigster',
                'token' => $token
                ], 200);
        }
        return response()->json([
            'login_id'=> $request->login_id,
            'status' => 'login',
            'token' => $token
            ], 200);
    
    
        }

    
    public function type(Request $request, $id){
	    $Type = ServiceType::with('category.services')->find($id);
	    $service = Service::whereHas('servicecategories', function ($query) use ($id) {
		$query->where('service_type_id', $id);
	    })->take(5)->get();
	    return response()->json(['type' => $Type, "popular_services" => $service], 200);
	}
    
    public function home(){
    $allServicesType = ServiceType::with('category.services')->get();

    // $userAppSetting = UserAppSetting::select('categories', 'baneer1', 'baneer2', 'baneer3', 'baneer4', 'law' )->first();
    $userAppSetting = UserAppSetting::with([
        'expert1',
        'expert1.comments.user','expert1.gallery',
        'expert2',
        'expert2.comments.user','expert2.gallery',
        'expert3',
        'expert3.comments.user','expert3.gallery',
        'expert4',
        'expert4.comments.user','expert4.gallery',
        ])
    ->select(
        'categories',
        'baneer1',
        'baneer2',
        'baneer3',
        'baneer4',
        'law',
        'expert_id1',
        'expert_id2',
        'expert_id3',
        'expert_id4'
    )->first();
    $categoryIds = $userAppSetting ? json_decode($userAppSetting->categories) : [];

      $categories = Servicecategory::whereIn('id', $categoryIds)
        ->with(['services' => function ($query) {
            $query->take(100); 
        }])
        ->take(15) 
        ->get();

	$popularServices = Service::all()->take(5); 
    return response()->json([
        'types' => $allServicesType,
        'categories' => $categories,
        'popular_services' => $popularServices,
        'appdata' => $userAppSetting  
    ], 200);	
    }

    public function orderservice(Request $request, $id){
        $service = Service::with('questions.predefinedAnswer')->find($id);
        
        return response()->json([
            'service' => $service,
            // 'questions' => $service->questions,
        ], 200);
    }

    public function addOrder(Request $request)
    {
        $validatedData = $request->validate([
            'service_id' => 'required|exists:services,id',
            'description' => 'nullable|string',
            'address' => 'required|string',
            'city' => 'required|string',
            'lat' => 'required',
            'log' => 'required',
            'completion_date' => 'nullable|date',
            'completion_time' => 'nullable|date_format:H:i',
            'answers' => 'required|array',
            'answers.*.question_id' => 'required|exists:questions,id',
            'answers.*.answer' => 'required|string',
        ]);

        // ایجاد سفارش جدید
        $order = Order::create([
            'user_id' => $request->user()->id,
            'service_id' => $validatedData['service_id'],
            'description' => $validatedData['description'],
            'address' => $validatedData['address'],
            'city' => $validatedData['city'],
            'completion_date' => $validatedData['completion_date'],
            'completion_time' => $validatedData['completion_time'],
            'lat' => $validatedData['lat'],
            'log' => $validatedData['log'],
            'status' => 1, // وضعیت سفارش: در حال بررسی
        ]);

        // ذخیره پاسخ‌های سفارش
        foreach ($validatedData['answers'] as $answer) {
            Answer::create([
                'order_id' => $order->id,
                'question_id' => $answer['question_id'],
                'answer' => $answer['answer'],
            ]);
	    }

	$orderLatitude = $validatedData['lat'];
    $orderLongitude = $validatedData['log'];
    $blockedExpertIds = Blocking::where('user_id', $request->user()->id)->pluck('expert_id');
    // پیدا کردن متخصصینی که سرویس مربوطه را ارائه می‌دهند
    NotifyNearbyExperts::dispatch(
        $validatedData['lat'],
        $validatedData['log'],
        Blocking::where('user_id', $request->user()->id)->pluck('expert_id')->toArray(),
        $validatedData['service_id']
    );

    return response()->json($order, 200);
}

private function haversineGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo)
{
    $earthRadius = 6371; // شعاع زمین به کیلومتر

    $latFrom = deg2rad($latitudeFrom);
    $lonFrom = deg2rad($longitudeFrom);
    $latTo = deg2rad($latitudeTo);
    $lonTo = deg2rad($longitudeTo);

    $latDelta = $latTo - $latFrom;
    $lonDelta = $lonTo - $lonFrom;

    $a = sin($latDelta / 2) * sin($latDelta / 2) +
         cos($latFrom) * cos($latTo) *
         sin($lonDelta / 2) * sin($lonDelta / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

    return $earthRadius * $c; // فاصله به کیلومتر
}
    public function orders(Request $request)
    {
        // گرفتن سفارشات بر اساس وضعیت
        $status = $request->query('status', null);

    $userId = $request->user()->id;

    // گرفتن لیست متخصصینی که کاربر بلاک کرده (فقط در صورت نیاز استفاده میشه)
    $blockedExpertIds = Blocking::where('user_id', $userId)->pluck('expert_id')->toArray();

    $orders = Order::where('user_id', $userId)
        ->with([
            'bids' => function ($query) use ($blockedExpertIds) {
                $query->where('is_active', true) // فقط پیشنهادهای فعال
                    ->where(function ($q) use ($blockedExpertIds) {
                        $q->whereHas('order', function ($q2) {
                            $q2->where('status', 2);
                        })->whereNotIn('expert_id', $blockedExpertIds);
                    })->orWhere(function ($q) {
                        $q->whereHas('order', function ($q2) {
                            $q2->where('is_active', true)->where('status', '!=', 2);
                        });
                    });
            },
            'bids.expert.comments.user',
            'bids.expert.gallery',
            'service',
            'bids.proposalType',
            'userReview',
            'answers.question'
        ])
        ->orderBy('updated_at', 'desc')
        ->get();

        return response()->json($orders);
    }

    public function order($id)
    {

        $order = Order::with('bids.expert.comments.user','bids.expert.gallery', 'service', 'bids.proposalType', 'review')->where('user_id', auth()->id())->findOrFail($id);
        
        Bid::where('order_id', $order->id)
            ->where('is_seen_by_user', false)
            ->update(['is_seen_by_user' => true]);
        
        return response()->json($order);
    }
	
    public function cancelOrder($id){
    	$order = Order::findOrFail($id);
        $order->update(
            [
                "status"=>"5"
            ]
        );
	return response()->json([
	"mesage"=>"سفارش با موفقیت لغو شد"
	]);

    }

    public function addreviews(Request $request, $id)
    {
        $request->validate([
            'comment' => 'nullable|string',
            'rating' => 'required|integer|min:1|max:5',
            'order_id' => 'required|exists:orders,id'
        ]);
	$user = expert::find($id);
	$order = Order::find($request->order_id);
	$order->update([
	  "status"=>"4"
	]);
	$review = Review::create([
            'user_id' => Auth::id(),
            'expert_id' => $user->id,
            'comment' => $request->comment,
            'rating' => $request->rating,
            'order_id' => $request->order_id,
        ]);

        return response()->json([
            'message' => 'نظر با موفقیت ثبت شد',
            'review' => $review
        ], 201);
    }

    public function editProfile(Request $request){
        $request->validate([
            'name' => 'required|string',
            'sex' => 'required|in:Male,Fmale'
        ]);

        $userDetails = Auth::user();  // To get the logged-in user details
        $user = User::find($userDetails ->id);
        $user->name = $request->name;
        $user->sex = $request->sex;
        $user->save();
        
        return response()->json([
            'message' => 'پروفایل با موفقیت ویرایش شد .',
        ], 200);
    }


    public function updateReview(Request $request, $orderId)
{
    $request->validate([
        'comment' => 'nullable|string',
        'rating' => 'required|integer|min:1|max:5',
    ]);

    // پیدا کردن Review بر اساس سفارش و کاربر
    $review = Review::where('order_id', $orderId)
                    ->where('user_id', Auth::id())
                    ->first();

    if (!$review) {
        return response()->json(['message' => 'نظر یافت نشد'], 404);
    }

    // به‌روزرسانی نظر
    $review->update([
        'comment' => $request->comment,
        'rating' => $request->rating,
        'is_active' => false
    ]);

    return response()->json([
        'message' => 'نظر با موفقیت ویرایش شد',
        'review' => $review
    ], 200);
}


    
    public function editprofileimage(Request $request){
        $user = User::find(Auth::user()->id);
        $image_path = $user->picture;

        if ($request->hasFile('image')) {

            if (file_exists(public_path($image_path) && $image_path != 'img/default.png')) {

                FacadesFile::delete(public_path($image_path));
                
            }

            $image = time().'.p.'.$request->image->extension();
            $request->image->move(public_path('img'), $image);
            
            $user->update([
                'picture'=>'img/'.$image
            ]);
        }

        return response()->json([
            'msg'=> "عملیات با موفقیت انجام شد"
            ], 200);
    }
    
  public function conformExpert(Request $req, $orderId, $expertId){
 
   $order = Order::find($orderId);

    if (!$order) {
        return response()->json(['message' => 'Order not found'], 404);
    }

    if (!Bid::where('order_id', $orderId)->where('expert_id', $expertId)->exists()) {
        return response()->json(['message' => 'Expert did not bid on this order'], 400);
    }

    DB::transaction(function () use ($order, $orderId, $expertId) {
        Bid::where('order_id', $orderId)
            ->where('expert_id', '!=', $expertId)
            ->update(['is_active' => false]); // فقط غیرفعال کن

        $order->status = 3; // مثلا تایید متخصص
        $order->save();
    });
    if ($expertId != null) {
            $expert = expert::find($expertId);
    try {
        if($expert->notification){
        if (!is_null($expert->fcm_token)) {
            $token = $expert->fcm_token;
            $fcm = new ExpertFirebaseNotificationService();
            $fcm->send(
            $token,
            'پیشنهاد تایید شد !',
            'پیشنهاد شما توسط کاربر تایید شد . 😎',
            ['customData' => '123']
            );
        }}
    } catch (\Throwable $th) {
        //throw $th;
    }
    }
    return response()->json(['message' => 'Expert confirmed and order status updated'], 200);
  }

  public function cancelExpert(Request $req, $orderId, $expertId)
{
    $order = Order::find($orderId);

    if (!$order) {
        return response()->json(['message' => 'Order not found'], 404);
    }

    DB::transaction(function () use ($order, $orderId) {
        // فعال کردن دوباره همه بیدها
        Bid::where('order_id', $orderId)->update(['is_active' => true]);

        // ریست کردن وضعیت سفارش (مثلاً 2 = در حال بررسی)
        $order->status = 2;
        $order->save();
    });
    $expert = expert::find($expertId);

    try {
        if($expert->notification){
        if (!is_null($expert->fcm_token)) {
            $token = $expert->fcm_token;
            $fcm = new ExpertFirebaseNotificationService();
            $fcm->send(
            $token,
            'پیشنهاد لغو شد !',
            'پیشنهاد شما توسط کاربر لغو شد !',
            ['customData' => '123']
            );
        }}
    } catch (\Throwable $th) {
        //throw $th;
    }

    return response()->json(['message' => 'Expert selection cancelled and bids restored'], 200);
}

    public function click(Request $request,$expertId, $serviceId)
    {
	$expert = Expert::find($expertId);

       if (!$expert) {
            return response()->json(['mesage' => 'متخصص پیدا نشد'], 404);
	}

    $service = Service::find($serviceId);

    if (!$service) {
        return response()->json(['mesage' => 'سرویس پیدا نشد'], 404);
    }

    $commission = $service->commission;
    $wallet = $expert->wallet;

    if ($wallet->balance < $commission) {
        return response()->json(['mesage' => 'متخصص در دسترس نیست'], 400);
    }
    //$wallet = $expert->wallet;
    $wallet->balance = $wallet->balance - $commission;
    $wallet->save();

    return response()->json(['mesage' => 'کمیسیون با موفقیت کسر شد'], 200);
}
  public function law(Request $req){

	  $userappsetting = UserAppSetting::all()->first();

	  return response()->json([
	 	'law'=> $userappsetting->law 
	  ], 200);
  }

  public function services(){
	  $services = Service::all();
	return response()->json($services);
  }
    public function notifs(Request $request)
    {
        $seen = $request->seen;
        $user = Auth::user();
        $notifs = UserNotification::where('user_id', $user->id)->latest()->get();
	if($seen){
	   UserNotification::where('user_id', $user->id)->update(['seen' => true]);
	}
	$Noseen = UserNotification::where(['user_id'=> $user->id, 'seen'=>false])->get();
        return response()->json([
            "notif"=>$notifs,
	    "noseen"=>$Noseen
        ], 200);
    }

    public function suportSendMessage(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string'
        ]);

        SupportMessages::create([
            'conversation_id' => $id,
            'sender_type' => 'user',
            'sender_id' => auth()->id(),
            'message' => $request->message
        ]);

        return response()->json([
		"message"=>"پیام ارسال شد."
	]);
    }

    public function suportShow(Request $request)
    {
        $conversation = SupportConversations::firstOrCreate([
            "user_id" => $request->user()->id,
        ]);

        $conversation->load('messages');

        return response()->json($conversation);
    }

    public function updateFcmToken(Request $request)
    {
        $request->validate(['fcm_token' => 'required|string']);

        $user = auth()->user(); // یا auth('expert')->user()
        $user->update(['fcm_token' => $request->fcm_token]);

        return response()->json(['message' => 'توکن با موفقیت ذخیره شد']);
    }

    public function blockExpert(Request $request)
    {
        Blocking::firstOrCreate([
            'user_id' => auth()->id(),
            'expert_id' => $request->expert_id,
            'block_type' => "user"
        ]);

        
        // Conversation::firstOrCreate([
        //     'user_id' => auth()->id(),
        //     'expert_id' => $request->expert_id,
        // ])
        // ->delete();
            //$conversation->update(['seen', 1]);

        return response()->json(['message' => 'متخصص با موفقیت بلاک شد']);
    }

    public function unblockExpert(Request $request)
    {
        Blocking::where('user_id', auth()->id())
            ->where('expert_id', $request->expert_id)
            ->where('block_type', "user")
            ->delete();

        return response()->json(['message' => 'متخصص از لیست بلاک حذف شد']);
    }

    public function BlockedExperts(Request $request) {
        $blockedExperts = Blocking::where('user_id', auth()->id())
        ->where('block_type', "user")
        ->with('expert')->get();
        return  response()->json(['experts' => $blockedExperts]);;
    }
}
