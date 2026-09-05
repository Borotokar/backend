<?php

namespace App\Http\Controllers;
use Config;
use App\Models\Service;
use App\Models\Bid;
use App\Models\expert;
use App\Models\ExpertAppSetting;
use App\Models\ExpertDocuments;
use App\Models\ExpertGallery;
use App\Models\ExpertVerifications;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\ExpertNotification;
use App\Models\VerificationCode;
use App\Models\Wallet;
use App\Models\Blocking;
use Morilog\Jalali\Jalalian;
use App\Models\Conversation;
use App\Models\Message;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Kavenegar\KavenegarApi;
use Kavenegar;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File as FacadesFile;
use Shetabit\Multipay\Exceptions\InvalidPaymentException;
use Shetabit\Multipay\Invoice;
use Shetabit\Multipay\Payment as MultipayPayment;
use Shetabit\Payment\Facade\Payment;
use App\Models\SupportMessages;
use App\Models\SupportConversations;
use App\Services\FirebaseNotificationService;

class ExpertController extends Controller
{
    public function me(Request $request){
        $user = expert::with('gallery', 'BlueTickdocuments','documents', 'services', 'Wallet','comments','video', 'bids.order.service', 'transactions', 'Allcomments.user', 'Allcomments.order.service')->find(Auth::user()->id);

        return response()->json([
                'user'=> $user
            ], 200);
    }
    public function login(Request $request){

        $request->validate([
            'login_id' => 'required',
        ],
        [
            'login_id.required' => 'Phone or Username is required',
            'login_id.exists' => 'Phone or Username isnot required',
        ]);
    
	$user = expert::where('phone_number', $request->login_id)->first();	
	if (!$user) {

                
                return response()->json([
                    'msg'=> 'متخصصی با این شماره تلفن وجود ندارد ابتدا ثبت نام کنید'
                    ], 200);
         }
	
	
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
        // $api = new KavenegarApi( "445A693566472F757349713846345544735933486F6A59506E4E415775374B5632684E415956584B464B413D" );
        $res = array($request->login_id);
    
        $receptor = $request->login_id;
        // $token = $code->otp;

        $message = "<#> کد فعال سازی حساب کاربری\ncode: ".$code->otp."\nبروتوکار | borotokar.com\ndxe+nz+/vtY";
        $result = Kavenegar::Send("9982001368", $res, $message);   

        // $result = $api->VerifyLookup($receptor, $token, $token2, $token3, $template, $type = null);
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
            $user = expert::where('phone_number', $request->login_id)->first();
    
            if (!$user) {

                $verificationCode->delete();
                return response()->json([
                    'msg'=> 'همچین متخصصی وجود ندارد ! ابتدا ثبت نام کنید'
                    ], 200);
            }
    
            $token = Auth::guard('expert')->login($user, true);
            $verificationCode->delete();

            return response()->json([
                'login_id'=> $request->login_id,
                'status' => 'login',
                'token' => $token
                ], 200);
        
    }

    
    public function rigister(Request $request){
        $request->validate([
            'login_id' => 'required',
        ],
        [
            'login_id.required' => 'Phone or Username is required',
            'login_id.exists' => 'Phone or Username isnot required',
        ]);
    
        //geneate otp
        $verificationCode = VerificationCode::where('phone', $request->login_id)->latest()->first();
        $user = expert::where('phone_number', $request->login_id)->first();
        
        if($user){
            return response()->json([
                'login_id'=> $request->login_id,
                'msg' => "متخصصی با این شماره وجود دارد."
                ], 200);
        }

        $now = Carbon::now();
    
        if($verificationCode && $now->isBefore($verificationCode->expire_at)){
            return response()->json([
                'login_id'=> $request->login_id,
                'msg' => "یک کد برای شما ارسال شده لطفا بعد از ده دقیقه تلاش کنید"
                ], 200);
        }
    

        $code = VerificationCode::create([
            'phone' => $request->login_id,
            'otp' => rand(123456, 999999),
            'expire_at' => Carbon::now()->addMinutes(5),
            'ip' => $request->ip()
        ]);
        // // todo send otp sms
    
        try{
        // $api = new KavenegarApi( "445A693566472F757349713846345544735933486F6A59506E4E415775374B5632684E415956584B464B413D" );
        $res = array($request->login_id);
    
        $receptor = $request->login_id;

        $message = "<#> کد فعال سازی حساب کاربری\ncode: ".$code->otp."\nبروتوکار | borotokar.com\n dxe+nz+/vtY";
        $result = Kavenegar::Send("9982001368", $res, $message);   
        // $token = $code->otp;
        // $token2 = null;
        // $token3 = null;
        // $template="expert";
        // $result = $api->VerifyLookup($receptor, $token, $token2, $token3, $template, $type = null);
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

        public function otp_rigister(Request $request){
        
        $request->validate([
            'login_id' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'national_id' => 'required',
            'birth_date'=> 'required',
            'otp' => 'required|digits:6'
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
                'message'=> "کد وارد شده اشتباه می باشد"
                ], 200);
        }
        elseif($verificationCode && $now->isAfter($verificationCode->expire_at)){
            $verificationCode->delete();

            return response()->json([
                'message'=> "مدت اعتبار کد شما به پایان رسیده لطفا دوباره اقدام کنید"
                ], 200);
        }

        elseif($verificationCode->ip != $request->ip()){
            return response()->json([
                'message'=> "شما اجازه ورود با این ip را ندارید"
                ], 503);
        }


    $response = Http::withHeaders([
            'Authorization' => 'Bearer b20aa9dc159b3303757ca557f6ebf206d48a0fa5', // توکن سرویس شاهکار
            'Accept' => 'application/json',
        ])->post('https://service.zohal.io/api/v0/services/inquiry/shahkar', [
            'mobile' => $request->login_id,
            'national_code' => $request->national_id,
        ]);

    // بررسی پاسخ دریافت شده
    if ($response->successful()) {
 

 
	$responseData = $response->json();
        
        // بررسی اگر matched true ب

        if($responseData['result']==1){
	
	if ($responseData['response_body']['data']['matched']) {
            // ادامه فرآیند ثبت‌نام
            $user = expert::create([
                'phone_number' => $request->login_id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'national_id' => $request->national_id,
                'birth_date' => $request->birth_date,
                'address' => "#",
                'province' => "#",
                'city' => "#",
                'lat'=>30.0,
                'log'=>30.0
            ]);
    
	    //$user->wallet =  new Wallet();	    
	       
	    //$user->wallet = $wallet;
	    //$user->save();
	    $wallet = new Wallet(['balance' => 0]); // موجودی اولیه کیف پول 0
	    $user->wallet()->save($wallet);

            $token = Auth::guard('expert')->login($user, true);
            $verificationCode->delete();
    
            return response()->json([
                'login_id'=> $request->login_id,
                'status' => 'login',
                'user_id'=> $user->id,
                'token' => $token
                ], 200);
        } 
            // اگر matched false بود، خطا برگردانید
      }
    } else if($responseData['result']==6){
        // خطای سرویس شاهکار
        return response()->json(['message' => 'شماره تلفن با کدملی مطابقت ندارد! ', 'erore'=>$response->json()], 500);
    }
//	
 }     
         
     public function edit_expert(Request $request){
        $expert = expert::find(Auth::user()->id);

        $request->validate([
            'first_name' => 'nullable|string',
            'last_name' => 'nullable|string',
            'type' => 'nullable|in:business_unit,self_employed,company',
            'telegram_link' => 'nullable|string',
            'whatsapp_link' => 'nullable|string',
            'eitaa_link' => 'nullable|string',
            'address' => 'nullable|string',
            'province' => 'nullable|string',
            'city' => 'nullable|string',
            //'lat' => 'nullable|decimal',
            //'log' => 'nullable|decimal',
	    'guarantee'=>'nullable|string',
	    'website_link'=>'nullable|string',
	    'about_me'=>'nullable|string',
	   'sms_notification'=>'nullable|boolean', 
	   'notification'=>'nullable|boolean', 
	   'is_blue_tick_request'=>'nullable|boolean', 
        ],
    );

	$input = collect(request()->all())->filter()->all();
    $expert->update(request()->all());
    $expert->save();

    if ($request->has('services')) {
        $expert->services()->sync($request->services);
    }

    return response()->json([
        'msg'=> "اطلاعات با موفقیت وارد شد."
        ], 200);
    }

    public function editprofileimage(Request $request){
        $expert = expert::find(Auth::user()->id);
        $image_path = $expert->profile_image;
        if ($request->hasFile('image')) {

            if (file_exists(public_path($image_path) && $image_path != 'img/default.png')) {

                FacadesFile::delete(public_path($image_path));
                
            }

            $image = time().'.p.'.$request->image->extension();
            $request->image->move(public_path('img'), $image);
            $expert->update([
                'profile_image'=>'img/'.$image
            ]);
        }

        return response()->json([
            'msg'=> "عملیات با موفقیت انجام شد"
            ], 200);
    }
    public function services(){
    	$services = Service::all();
	return response()->json(
            $services
	, 200);
    }

    public function editgallery(Request  $request){

        $request->validate([
            'gallery' => 'required|array|max:12',  
        ],
        );
        $expert = expert::find(Auth::user()->id);

        $existingImagesCount = $expert->gallery()->count();
        if ($existingImagesCount + count($request->gallery) > 12) {
            return response()->json(['error' => 'شما تنها 12 عکس میتوانید برای نمونه کار خود بگذارید.'], 400);
        }

        if ($request->hasFile('gallery')) {
            $gallery = [];
            foreach ($request->file('gallery') as $key => $file) {
                // $path = $file->store('gallery', 'public');
                $filename = $key.time().'.g.'.$file->extension();
                $file->move(public_path('img'), $filename);
                $gallery[$key] = $filename;
            }
            // $expert->gallery = json_encode($gallery);
            foreach ($gallery as $filename) {
                $image = new ExpertGallery();
                $image->expert_id = $expert->id;
                $image->path = 'img/'.$filename;
                $image->save();
            }
        }

        return response()->json([
            'msg'=> "عملیات بت موفقیت انجام شد "
            ], 200);
    }

    public function deleteImage(Request $request, $id){
	$image = ExpertGallery::find($id);
	$image->delete();
	return response()->json([
            'msg'=> "ﻊﻤﻠﯾﺎﺗ ﺐﺗ ﻡﻮﻔﻘﯿﺗ ﺎﻨﺟﺎﻣ ﺵﺩ "
            ], 200);
    }

    public function editdocuments(Request $request){
        $expert = expert::find(Auth::user()->id);
        
        if ($request->hasFile('documents')) {
            $documents = [];
            foreach ($request->file('documents') as $key => $file) {
                $rand = rand(1,99900);
                $filename = time().$rand.$key.'.d.'.$file->extension();
                $file->move(public_path('img'), $filename);
                $documents[$key] = $filename;
            }
            foreach ($documents as $file) {
                $doc = new ExpertDocuments();
                $doc->expert_id = $expert->id;
                $doc->type = $expert->type;
                $doc->path = 'img/'.$file;
                $doc->save();
            }
    
        }
    return response()->json([
        'msg'=> "اطلاعات با موفقیت وارد شد."
        ], 200);
    }

    public function editBlueTickDocuments(Request $request){
        $expert = expert::find(Auth::user()->id);
        
        if ($request->hasFile('documents')) {
            $documents = [];
            foreach ($request->file('documents') as $key => $file) {
                $rand = rand(1,99900);
                $filename = time().$rand.$key.'.b.'.$file->extension();
                $file->move(public_path('img'), $filename);
                $documents[$key] = $filename;
            }
            foreach ($documents as $file) {
                $doc = new ExpertDocuments();
                $doc->expert_id = $expert->id;
                $doc->type = "blueTick";
                $doc->path = 'img/'.$file;
                $doc->save();
            }
    
        }
    return response()->json([
        'msg'=> "اطلاعات با موفقیت وارد شد."
        ], 200);
    }

    public function order(Request $request){
        $expertId = Auth::id();
        $services = Auth::user()->services->pluck('id');
        $today = Jalalian::now()->format('Y-m-d');
        // مختصات جغرافیایی از request
        $lat = $request->lat;
        $log = $request->long;

        // فاصله مجاز بر حسب متر (مثلاً 50 کیلومتر)
        $radius = 50;

        $blockedUserIds = Blocking::where('expert_id', $expertId)->pluck('user_id');

        // سفارشات جدید: سفارشاتی که با شهر و خدمات متخصص مطابقت دارند و هنوز پیشنهادی از طرف این متخصص برای آنها ثبت نشده است

        $newOrders = Order::whereNotIn('user_id', $blockedUserIds)
        ->select(DB::raw("*,
            ( 6371 * acos( cos( radians($lat) ) * cos( radians( lat ) ) 
            * cos( radians( log ) - radians($log) ) + sin( radians($lat) ) 
            * sin( radians( lat ) ) ) ) AS distance"))

            ->having('distance','<',  $radius)

	    ->whereIn('service_id', $services)
    	    ->with('service')
            ->with('service.proposalTypes')

            ->whereDoesntHave('bids', function ($query) use ($expertId) {
                $query->where('expert_id', $expertId);
	    })
	    ->withCount(['bids' => function($query){
	    	$query->select(DB::raw('COUNT(*)'));
	    }])
            ->whereHas('bids', function($query) {
                $query->havingRaw('COUNT(*) < ?', [10]); // فیلتر کردن سفارشاتی که کمتر از 10 پیشنهاد دارند
            }, '<', 10)

            ->with('answers.question', 'user') // افزودن سوالات و پاسخ‌ها
            ->whereIn('status', [1,2])
            ->latest()
	    ->get();

	$eOrders = Order::select(DB::raw("*,
            ( 6371 * acos( cos( radians($lat) ) * cos( radians( lat ) )
            * cos( radians( log ) - radians($log) ) + sin( radians($lat) )
            * sin( radians( lat ) ) ) ) AS distance"))

            ->having('distance','<',  $radius)

            ->whereIn('service_id', $services)
            ->with('service')
            ->with('service.proposalTypes')

            ->whereDoesntHave('bids', function ($query) use ($expertId) {
                $query->where('expert_id', $expertId);
            })
            ->withCount(['bids' => function($query){
                $query->select(DB::raw('COUNT(*)'));
            }])
            ->whereHas('bids', function($query) {
                $query->havingRaw('COUNT(*) < ?', [10]); // ﻒﯿﻠﺗﺭ ﮎﺭﺪﻧ ﺲﻓﺍﺮﺷﺎﺘﯾ ﮏﻫ ﮏﻤﺗﺭ ﺍﺯ 10 ﭗﯿﺸﻨﻫﺍﺩ ﺩﺍﺮﻧﺩ
            }, '<', 10)

            ->with('answers.question', 'user') // ﺎﻓﺯﻭﺪﻧ ﺱﻭﺍﻼﺗ ﻭ ﭖﺎﺴﺧ<200c>ﻫﺍ
            ->whereIn('status', [3,4,5])
            ->latest()
            ->take(5)
	    ->get();

        // پیشنهادهای ارسال شده: سفارشاتی که متخصص قبلاً برای آنها پیشنهاد داده است
        $sentBids = Order::whereHas('bids', function ($query) use ($expertId) {
            $query->where('expert_id', $expertId);
	})
	->with('service')
	->with('user')
        ->with(['bids' => function ($query) use ($expertId) {
            $query->where('expert_id', $expertId);
        }, 'answers.question']) // فقط پیشنهادهای متخصص فعلی و سوالات و پاسخ‌ها
	->withCount(['bids' => function($query){
		$query->select(DB::raw('COUNT(*)'));
        }])
    ->whereIn('status', [2,3])

    ->latest()
	->get();

        // سفارشات تمام شده: سفارشاتی که وضعیت آنها تکمیل شده است و نظرات مرتبط به آن
        $completedOrders = Order::where('status', 4)
            ->whereHas('bids', function ($query) use ($expertId) {
                $query->where('expert_id', $expertId);
	    })
	    ->with('service')
    	    ->with('user')
            ->with('review')
	    // ->whereHas('review')
	    ->get();

        return response()->json([
            'new_orders' => $newOrders,
            'sent_bids' => $sentBids,
            'completed_orders' => $completedOrders,
	        'e_orders' => $eOrders,
        ]);
    }

    function filterSensitiveInfo($text)
    {
        // شماره‌های موبایل فارسی (۰۱۲۳...) رو به انگلیسی تبدیل کنیم اول
        $text = convertPersianNumbersToEnglish($text);

        // حذف فاصله‌ها یا خط‌ فاصله‌های بین ارقام (مثلاً 0912-123-4567)
        $text = preg_replace('/(\+98|0)?9[\s\-]?\d{3}[\s\-]?\d{4}/', '***', $text);

        // حذف آیدی‌ها (تلگرام/اینستاگرام/سوشیال) که با @ شروع می‌شن
        $text = preg_replace('/@[\w_.]+/', '***', $text);

        return $text;
    }

    function convertPersianNumbersToEnglish($text)
    {
        $persian = ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'];
        $english = ['0','1','2','3','4','5','6','7','8','9'];
        return str_replace($persian, $english, $text);
    }

    public function sendbids(Request $request, $id){
        
        $validated = $request->validate([
            'proposed_price' => 'required',
            //'type' => 'required|in:hourly,whole_job',
	    'description' => 'nullable|string',
	    'proposal_type_id' => 'required|exists:proposal_types,id',
        ]);

        $order = Order::find($id);
        $expert = Auth::user();
        $balance = $expert->wallet->balance;
	
        if ($order->bids()->where('expert_id', $expert->id)->exists()) {
        return response()->json([
            'message' => 'شما قبلاً برای این سفارش یک پیشنهاد ارسال کرده‌اید.',
        ], 400);
        }

        if (!$expert->is_active) {
            return response()->json([
                'message' => 'حساب کاربری شما تایید نشده است',
            ], 400);
        }

        if ($order->bids()->count() >= 10) {
            return response()->json([
                'message' => 'بیش از 10 پیشنهاد نمی‌توان ارسال کرد .',
            ], 400);
	    }

        $bidPrice = $order->service->commission;
        if($bidPrice > $balance){
            return response()->json([
                'message' => 'موجودی شما کافی نیست .',
            ], 400);
        }



        // $description = filterSensitiveInfo($validated['description']);

        
        $bid = new Bid([
            'proposed_price' => $validated['proposed_price'],
            'type' => 'whole_job',
            'description' => $validated['description'],
            'expert_id' => $request->user()->id, // فرض بر این است که کاربر وارد شده، متخصص است.
            'proposal_type_id' => $validated['proposal_type_id'],
            'is_seen_by_user' => false,
        ]);
	
        // send notif to user

        try {
            if (!is_null($order->user->fcm_token)) {
            $fcm = new FirebaseNotificationService();
            $fcm->send(
                $order->user->fcm_token,
                'یک پیشنهاد برای سفارش شما ارسال شد',
                'همین الان به بروتوکار سر بزن 😎',
                ['customData' => '123']
            );
            }
        } catch (\Throwable $th) {
            //throw $th;
        }

        $conversation = Conversation::firstOrCreate([
                    "user_id"=>$order->user->id,
                    "expert_id"=>$expert->id,
        ]);

        $completion_time = Carbon::parse($order->completion_time);
        $endTime = $completion_time->copy()->addHours(2);
            
        $expert_msg = 'درود ، پیشنهاد متخصص برای کار  : ' . $order->service->title . "\n- تاریخ: " . date('d-m-Y', strtotime($order->completion_date)) . "\n- ساعت: " . date('H:i', $completion_time->timestamp) . ' تا ' . date('H:i', $endTime->timestamp) . "\n- دستمزد: " . $bid->proposalType->name ." " . number_format($bid->proposed_price) . ' تومان' . "\n- توضیحات: " . $bid->description;   
        // $user_msg = "سفارش  : ".$order->service->title. " تاریخ انجام  : " . $order->completion_date . " زمان شروع کار : " . $order->completion_time ;

        //     $user_message = new Message([
        //     "sender_id"=>$order->user->id,
        //     "sender_type"=>"user",
        //     "message"=>$user_msg,
        //     ]);
        
        $message = new Message([
            "sender_id"=>$order->user->id,
            "sender_type"=>"expert",
            "message"=>$expert_msg,
            ]);
        $conversation->update(['seen'=> false]);
        // $conversation->messages()->save($user_message);
        $conversation->messages()->save($message);
            // اضافه کردن پیشنهاد به سفارش
            $order->bids()->save($bid);
        $order->update([
            "status"=>"2"
        ]
        );

        $expert->wallet->balance -= $bidPrice;
        $expert->wallet->save();
        return response()->json([
                'message' => 'پیشنهاد با موفقیت ارسال شد',
                'bid' => $bid,
            ], 201);
    }
    
    public function topUp(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:250000',
	'id'=>'exists:experts,id'		
	]);

        
        $expert = expert::find($request->id);
        $orderId = rand(123456789, 999999999);
        $amount = $request->amount + ($request->amount/10);

        $invoice = new Invoice;
        $invoice
            ->amount($amount);
            //->transactionId($orderId);
            // ->orderId($orderId);
	$invoice->detail('orderId',$orderId);
        $transaction = new Transaction();

        $transaction->expert_id = $expert->id;
        $transaction->amount = $request->amount;
        $transaction->ip = $request->ip();

	$payConfig = config('payment');
	
	//return response($payConfig);
	$payment = new MultipayPayment($payConfig);
    //    $transaction->transaction_id = $invoice->getUuid();
//	 $transaction->save();

	return $payment->purchase(
            $invoice,
             function($driver, $transactionId) use ($transaction){
               $transaction->transaction_id = $transactionId;
	      $transaction->save();
	       
            }
        )->pay()->render();

	
        // return response()->json([
        //     'message' => 'Failed to initiate payment',
        //     'transaction' => $transaction,
        // ], 500);
    }

    public function topUpCallback(Request $request)
    {
	//    return $request;
	$payConfig = config('payment');
       $payment = new MultipayPayment($payConfig);


	try {
            
	     $transaction = Transaction::all()->where('transaction_id', $request->Token)->where('status', 'pending')->first();
             if (is_null($transaction)){
                 return response()->json([
                     'message' => 'ﺶﻣﺍﺮﻫ ﻑﺎﮑﺗﻭﺭ ﻢﻌﺘﺑﺭ ﻦﻤﯾ ﺏﺎﺷﺩ'
                ]);
             }
	
            

            $receipt = $payment->amount($transaction->amount)->transactionId($request->Token)->verify();
            $transaction->status = 'completed';
            $transaction->save();

            $expert = expert::find($transaction->expert_id);
            $wallet = $expert->wallet;
            $wallet->balance += $transaction->amount;
            $wallet->save();

            
	    $data = [
		      'order_id' => $receipt->getReferenceId(),
		        'amount' => $transaction->amount,
		        'transaction'=> $transaction,
		         'message' => 'ﺵﺍﺭﮊ ﺢﺳﺎﺑ ﺏﺍ ﻡﻮﻔﻘﯿﺗ ﺎﻨﺟﺎﻣ ﺵﺩ'
		     ]; 

           // return response()->json([
           //   'order_id' => $receipt->getReferenceId(),
           //    'amount' => $wallet,
           //     'transaction'=> $transaction,
           //     'message' => 'شارژ حساب با موفقیت انجام شد'
	   //  ]);
	return view('page.transaction', $data);

         } catch (InvalidPaymentException $exception) {


            return response()->json([
                'message' => $exception->getMessage(),
            ]);
        }

            return response()->json([
                'message' => 'Payment fail',
            ]);
        }

        public function upload(Request $request)
        {
            $request->validate([
                'video' => 'required|mimes:mp4,mov,avi|max:10000', // تنظیمات مربوط به حجم و نوع فایل
            ]);
            
            $path = time().'.v.'.$request->video->extension();
            $request->video->move(public_path('video'), $path);

    
            $verification = ExpertVerifications::create([
                'expert_id' => auth()->id(), // فرض بر این است که متخصص وارد شده است
                'video_path' => 'video/'.$path,
            ]);
    
            return response()->json([
                'message' => 'Video uploaded successfully',
                'verification' => $verification,
            ], 201);
        }

        public function law(Request $request){
            $setting = ExpertAppSetting::all()->first();
            if (empty($setting)) {
                $setting = new ExpertAppSetting();
                $setting->law = 'null';
                $setting->save();
            }

            return response()->json([
                'law' => $setting->law,
            ], 201);
        }

    public function suportSendMessage(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string'
        ]);

        SupportMessages::create([
            'conversation_id' => $id,
            'sender_type' => 'expert',
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
            "expert_id" => auth()->id(),
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

    public function blockUser(Request $request)
    {
        Blocking::firstOrCreate([
            'user_id' => $request->user_id,
            'expert_id' => auth()->id(),
            'block_type' => "expert"
        ]);

        
        // Conversation::firstOrCreate([
        //     'user_id' => $request->user_id,
        //     'expert_id' => auth()->id(),
        // ])
        // ->delete();
            //$conversation->update(['seen', 1]);

        return response()->json(['message' => 'متخصص با موفقیت بلاک شد']);
    }

    public function unblockUser(Request $request)
    {
        Blocking::where('expert_id', auth()->id())
            ->where('user_id', $request->user_id)
            ->where('block_type' , "expert")
            ->delete();

        return response()->json(['message' => 'متخصص از لیست بلاک حذف شد']);
    }

    public function BlockedUsers(Request $request) {
        $blockedExperts = Blocking::where('expert_id', auth()->id())
        ->where('block_type', "expert")
        ->with('user')->get();
        return  response()->json(['users' => $blockedExperts]);
    }

    public function notifs(Request $request)
    {
        $seen = $request->seen;
        $user = Auth::user();
        $notifs = ExpertNotification::where('expert_id', $user->id)->latest()->get();
        if($seen){
            ExpertNotification::where('expert_id', $user->id)->update(['seen' => true]);
        }
        $Noseen = ExpertNotification::where(['expert_id'=> $user->id, 'seen'=>false])->get();
        return response()->json([
            "notif"=>$notifs,
            "noseen"=>$Noseen
        ], 200);
    }

    function reviewRequest(Request $request, $id)  {
        $order = Order::find($id);
        $expert = $request->user();
        $blokingexist = Blocking::where('user_id', $order->user_id)->where('expert_id', $expert->id)->exists();
        if ($blokingexist) {
            return response()->json(['message' => 'شما اجازه دسترسی به کاربر را ندارید !'], 403);   
        }
        try {
            if (!is_null($order->user->fcm_token)) {
            $fcm = new FirebaseNotificationService();
            $fcm->send(
                $order->user->fcm_token,
                // $expert->first_name . ' '. $expert->last_name ,
                'نظرت در مورد کار متخصص چیه ؟',
                'به متخصص ' . $expert->first_name . ' ' . $expert->last_name . ' امتیاز بده ! 😎',
                ['customData' => '123']
            );
            }
        } catch (\Throwable $th) {
            //throw $th;
        }

        return response()->json(['message' => 'درخواست با موفقیت انجام شد .'], 200);   
    }
}
