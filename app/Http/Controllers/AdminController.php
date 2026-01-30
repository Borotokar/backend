<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Answer;
use App\Models\Bid;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\expert;
use App\Models\ExpertAppSetting;
use App\Models\ExpertDocuments;
use App\Models\ExpertGallery;
use App\Models\Notification;
use App\Models\Order;
use App\Models\AdminSession;
use Illuminate\Support\Facades\Hash;
use App\Models\Question;
use App\Models\Review;
use App\Models\Service;
use App\Models\UserNotification;
use App\Models\ExpertNotification;
use App\Models\Servicecategory;
use App\Models\ServiceType;
use App\Models\SupportMessages;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Models\Role;
use App\Models\User;
use App\Models\ProposalType;
use App\Models\UserAppSetting;
use Carbon\Carbon;
use Faker\Core\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File as FacadesFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Morilog\Jalali\Jalalian;
use Kavenegar;
use Illuminate\Support\Str;
use App\Services\ExpertFirebaseNotificationService;
use App\Services\FirebaseNotificationService;
use App\Jobs\LogAdminAction;

class AdminController extends Controller

{
    protected $maxAttempts = 1; // default is 5
    protected $decayMinutes = 3; // default is 1
    public function admins(Request $request) {
        $admins = Admin::all();
        return view('page.adminsList')->with('admins', $admins);
    }

    public function editAdmin(Request $request, $id){
        $admin = Admin::with('logs')->find($id);
        $roles = Role::all();
        return view('page.adminEdit')->with('admin', $admin)->with('roles', $roles);
    }
    public function editAdmin_handller(Request $request, $id){
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:admins,username,' . $id,
            'phone_number' => 'required|string|max:20|unique:admins,phone_number,' . $id,
            'picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password' => 'nullable|string|min:8|confirmed',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
        ]);
    
        // پیدا کردن مدیر
        $admin = Admin::findOrFail($id);
    
        // به‌روزرسانی اطلاعات
        $admin->name = $request->name;
        $admin->username = $request->username;
        $admin->phone_number = $request->phone_number;
    
        // به‌روزرسانی تصویر در صورت وجود
        // if ($request->hasFile('picture')) {
        //     $filePath = $request->file('picture')->store('img', 'public');
        //     $admin->picture = $filePath;
        // }
        $image_path = $admin->picture;
        
        if ($request->image != null) {
            $filename = time().'.'.$request->image->extension();
            $request->image->move(public_path('img'), $filename);

            if (file_exists(public_path($image_path))) {

                FacadesFile::delete(public_path($image_path));
                
            }

            $admin->update([
                'picture' => "img/".$filename,
            ]);
        }    
        // به‌روزرسانی رمز عبور در صورت وجود
        if ($request->password) {
            $admin->password = bcrypt($request->password);
        }
        $admin->roles()->sync($request->roles);
        // ذخیره تغییرات
        $admin->save();

        LogAdminAction::dispatch(
            auth('admin')->id(),
            'بروزرسانی_ادمین',
            'ادمین '.$admin->name.'بروز شد .',
            request()->ip(),
        );

        return redirect()->back()->with('success', 'اطلاعات مدیر با موفقیت به‌روزرسانی شد.');
    }

    public function AdminDelete($id)
    {
        $admin = Admin::findOrFail($id);
        
        LogAdminAction::dispatch(
            auth('admin')->id(),
            'حذف_ادمین',
            'ادمین '.$admin->name.' حذف شد .',
            request()->ip(),
        );
        
        $admin->delete();
        return redirect()->route('admin.admins')->with('success', 'مدیر با موفقیت حذف شد.');
    }  
    public function index(){
	$users = count(User::all());
	$todayUsers = count(User::whereDate('created_at', Carbon::today())->get());
	$todayExpert = count(expert::whereDate('created_at', Carbon::today())->get());
	$experts = count(expert::all());
        //$orders =  Order::whereDay('created_at', now()->day)->get();
	$orders = Order::whereDate('created_at', Carbon::today())->get();
	$reviews = Review::whereDate('created_at', Carbon::today())->get();
        //$startOfMonth = Carbon::now()->startOfMonth();
        //$endOfMonth = Carbon::now()->endOfMonth();
	$startOfMonth = Jalalian::now()->getFirstDayOfMonth()->toCarbon();
	$endOfMonth = Jalalian::now()->getEndDayOfMonth()->toCarbon();

	$startOfMonthJalali = Jalalian::fromCarbon($startOfMonth);
	$endOfMonthJalali = Jalalian::fromCarbon($endOfMonth);
	
	$notifications = Notification::orderBy('created_at', 'desc')->take(50)->get();
        // دریافت تمام پیشنهادات ارسال‌شده در ماه جاری
        $currentMonthBids = Bid::whereBetween('created_at', [$startOfMonth, $endOfMonth])->get();

        // محاسبه جمع کمیسیون‌ها
        $totalCommission = $currentMonthBids->sum(function ($bid) {
            return $bid->order->service->commission;
        });

        $currentMonthdepolist = Transaction::whereBetween('created_at', [$startOfMonth, $endOfMonth])->where('status', "completed" )->get();
        $totalDespolist = $currentMonthdepolist->sum(function ($transaction) {
            return $transaction->amount;
        });
        // $now = ;
        
        $unreadCount = SupportMessages::where('sender_type', 'user')
        ->where('is_read', false)
        ->count();

        $ExpertunreadCount = SupportMessages::where('sender_type', 'expert')
        ->where('is_read', false)
        ->count();
        return view('page.admin')->with('ExpertunreadCount', $ExpertunreadCount)->with('unreadCount', $unreadCount)->with('todayExpert', $todayExpert)->with('todayUsers', $todayUsers)->with('notifications', $notifications)->with('users', $users)->with('experts', $experts)->with('orders', $orders)->with('reviews', $reviews)->with('totalCommission', $totalCommission)->with('totalDespolist', $totalDespolist);
    }

    public function editprofile(){
        $admin = Auth::user();
        return view('page.EditProfile')->with('admin', $admin);
    }

    public function editprofilehandler(Request $request){
        $request->validate([
            'phone_number' => 'required|exists:admins,phone_number|digits:11',
            'name' => 'required',
            'username' => 'required',

        ],
        [
            'phone_number.required' => 'این فیلد الزامیست',
            'phone_number.exists' => '! شماره موبایل شما اشتباه است',
            'name.required' => 'این فیلد الزامیست',
            'username.required' => 'این فیلد الزامیست'
        ]);

        $admin = Admin::find(Auth::user()->id);
        $image_path = $admin->picture;
        
        if ($request->image != null) {
            $filename = time().'.'.$request->image->extension();
            $request->image->move(public_path('img'), $filename);

            if (file_exists(public_path($image_path))) {

                FacadesFile::delete(public_path($image_path));
                
            }

            $admin->update([
                'picture' => "img/".$filename,
            ]);
        }
        $admin->update([
            'phone_number' => $request->phone_number,
            'name' => $request->name,
            'username' =>$request->username,
        ]);




        return redirect()->route('admin.editprofile')->with('success', 'ویرایش با موفقیت انجام شد');
    }


    	public function search(Request $request)
    {	
    $query = Service::query();

    if ($request->has('search') && $request->search) {
        $searchTerm = $request->search;

        $query->where('title', 'LIKE',"%{$searchTerm}%")
              ->orWhereHas('servicecategories', function ($q) use ($searchTerm) {
                  $q->where('name', 'LIKE', "%{$searchTerm}%");
	      });
    }

    $services = $query->paginate(15);
    $cats = Servicecategory::all(); 
    $proposalTypes = ProposalType::all();
    return view('page.Service.ServiceList')->with('services', $services)->with('cats', $cats)->with('proposalTypes', $proposalTypes);
    }

    public function login_handller(Request $request){
        // check login id is username or phone numbber
        $fieldType = $fieldType = ctype_digit($request->login_id) ? 'phone_number' : 'username';

        if($fieldType == 'phone_number'){
            $request->validate([
                'login_id' => 'required|exists:admins,phone_number|digits:11',
                'password' => 'required|min:5|max:40',

            ],
            [
                'login_id.required' => 'این فیلد الزامیست',
                'login_id.exists' => '! پسورد یا شماره موبایل شما اشتباه است',
                'password.required' => 'این فیلد الزامیست'
            ]
        );

        }
        else if ($fieldType == 'username'){
            $request->validate([
                'login_id' => 'required|exists:admins,username',
                'password' => 'required|min:5|max:40',

            ],
            [
                'login_id.required' => 'این فیلد الزامیست',
                'login_id.exists' => '! پسورد یا شماره موبایل شما اشتباه است',
                'password.required' => 'این فیلد الزامیست'
            ]
        );
        }

        $cards = array(
            $fieldType => $request->login_id,
            'password'=> $request->password
        );

        if(Auth::guard('admin')->attempt($cards)){
            AdminSession::create([
                'admin_id' => auth('admin')->id(),
                'login_at' => now(),
                'ip'       => request()->ip(),
            ]);

            return redirect()->route('admin.home');
        }else {
            session()->flash('fail', 'Incorrect cardentials');
            return redirect()->route('admin.login');
        }

    }

    public function logout_handller (Request $request){
        Auth::guard('admin')->logout();

        // AdminSession::where('admin_id', auth('admin')->id())
        //     ->whereNull('logout_at')
        //     ->latest()
        //     ->first()
        //     ?->update(['logout_at' => now()]);


        session()->flash('fail', 'شما خارج شدید');
        return redirect()->route('admin.login');
    }


    // services functions

    public function services(){
        $ServiceLists = Service::paginate(15);
        $cats = Servicecategory::all();
	    $proposalTypes = ProposalType::all();
        return view('page.Service.ServiceList')->with('services' ,$ServiceLists)->with('cats', $cats)->with('proposalTypes', $proposalTypes);
    }

    public function addservicehandller(Request $request){
        if(! $request->isMethod('POST')){
            return abort(404);
        }
        $request->validate([
            'category' => 'required|exists:servicecategories,id',
            'title' => 'required|min:5|max:40',
            'des' => 'required|min:5|max:500',
            'commission' => 'required',
	    'image' => 'required',
	    'proposal_types' => 'array|required',
            'questions' => 'array',
            'questions.*.text' => 'required|string|max:255',
            'questions.*.type' => 'required|string|in:predefined,user',
            'questions.*.answers' => 'array',
            'questions.*.answers.*' => 'required_if:questions.*.type,predefined|string|max:255',

        ],
        [
            'category.required' => 'این فیلد الزامیست',
            'category.exists' => 'دسته بندی اشتباه است',
            'title.required' => 'این فیلد الزامیست',
            'des.required' => 'این فیلد الزامیست',
            'image.required' => 'این فیلد الزامیست',
            'questions.array' => '1 این فیلد الزامیست',
            'questions.*.text.required' => '1 این فیلد الزامیست',
            'questions.*.type.required' => '2 این فیلد الزامیست',
            'questions.*.type.required' => '2 این فیلد الزامیست',
            'questions.*.answers.*.required_if' => '3 این فیلد الزامیست',
            'questions.*.answers.array' => '4این فیلد باید به شکل آرایه باشد',

        ]);
        
        $filename = time().'.'.$request->image->extension();
        $request->image->move(public_path('img'), $filename);
        

        $service = new Service();
        $service->title = $request->title;
        $service->des = $request->des;
        $service->image = "img/".$filename;
        $service->servicecategory_id = $request->category;
	$service->commission = $request->commission;
	//$service->proposalTypes()->sync($request->proposal_types);
        $service->save();
        $service->proposalTypes()->sync($request->proposal_types);
        foreach ($request->input('questions', []) as $questionData) {
            $questionText = $questionData['text'] ?? null;
            $questionType = $questionData['type'] ?? null;
    
            if ($questionText && $questionType) {
                
                $question = new Question();
                
                $question->service_id = $service->id;
                $question->question = $questionText;
                $question->type = $questionType;
                $question->save();
    
                if ($questionType === 'predefined' && isset($questionData['answers'])) {
                    foreach ($questionData['answers'] as $answer) {
                        $question->predefinedAnswer()->create([
                            'answer' => $answer,
                        ]);
                    }
                }
            }
        }

        LogAdminAction::dispatch(
            auth('admin')->id(),
            'افزودن_خدمت',
            'خدمت '.$service->title.' اضافه شد .',
            request()->ip(),
        );
        return redirect()->route('admin.services')->with('success', 'خدمت با موفقیت اضافه شد');

    }

    public function deleteService(Request $request, $id){
        $service = Service::find($id);

        $image_path = $service->image;
        // Storage::delete('/'.$image_path);
        if (file_exists(public_path($image_path))) {

            FacadesFile::delete(public_path($image_path));
            
        }
        LogAdminAction::dispatch(
            auth('admin')->id(),
            'حذف_خدمت',
            'خدمت '.$service->title.' حذف شد .',
            request()->ip(),
        );
        $service->delete();
        return redirect()->route('admin.services')->with('success', 'خدمت با موفقیت حذف شد');
        // return response();
    }

    public function editService($id){
        $service = Service::with('questions.predefinedAnswer')->findOrFail($id);
        $cats = Servicecategory::all();
	$proposalTypes = ProposalType::all();
        return view('page.Service.ServiceEdit')->with('service', $service)->with('cats', $cats)->with('proposalTypes', $proposalTypes); 
    }

    public function updateService(Request $request, $id){
        
        $request->validate([
            'category' => 'required|exists:servicecategories,id',
            'title' => 'required|min:5|max:40',
            'des' => 'required|min:5|max:500',
            'commission' => 'required',
            // 'image' => 'required',
	    'proposal_types' => 'array|required',
	    'questions' => 'array',
            'questions.*.text' => 'required|string|max:255',
            'questions.*.type' => 'required|string|in:predefined,user',
            'questions.*.answers' => 'array',
            'questions.*.answers.*' => 'required_if:questions.*.type,predefined|string|max:255',

        ],
        [
            'category.required' => 'این فیلد الزامیست',
            'category.exists' => 'دسته بندی اشتباه است',
            'title.required' => 'این فیلد الزامیست',
            'des.required' => 'این فیلد الزامیست',
            // 'image.required' => 'این فیلد الزامیست',
            'questions.array' => '1 این فیلد الزامیست',
            'questions.*.text.required' => '1 این فیلد الزامیست',
            'questions.*.type.required' => '2 این فیلد الزامیست',
            'questions.*.type.required' => '2 این فیلد الزامیست',
            'questions.*.answers.*.required_if' => '3 این فیلد الزامیست',
            'questions.*.answers.array' => '4این فیلد باید به شکل آرایه باشد',

        ]);

        $service = Service::findOrFail($id);
        $image_path = $service->image;
        
        
        if ($request->image != null) {
            $filename = time().'.'.$request->image->extension();
            $request->image->move(public_path('img'), $filename);

            if (file_exists(public_path($image_path))) {

                FacadesFile::delete(public_path($image_path));
                
            }

            $service->update([
                'image' => "img/".$filename,
            ]);
        }

        $service->update([
            'title' => $request->title,
            'des' => $request->des,
            'servicecategory_id' => $request->category,
            'commission' => $request->commission,
	]);

	$service->proposalTypes()->sync($request->proposal_types);


        $service->questions()->delete();

        foreach ($request->input('questions', []) as $questionData) {
            $questionText = $questionData['text'] ?? null;
            $questionType = $questionData['type'] ?? null;
    
            if ($questionText && $questionType) {
                $question = $service->questions()->create([
                    'question' => $questionText,
                    'type' => $questionType,
                ]);
    
                if ($questionType === 'predefined' && isset($questionData['answers'])) {
                    foreach ($questionData['answers'] as $answer) {
                        $question->predefinedAnswer()->create([
                            'answer' => $answer,
                        ]);
                    }
                }
            }
        }

        LogAdminAction::dispatch(
            auth('admin')->id(),
            'ویرایش_خدمت',
            'خدمت '.$service->title.' ویرایش شد .',
            request()->ip(),
        );

        return redirect()->route('admin.services')->with('success', 'خدمت با موفقیت ویرایش شد');
        // return response($request->input('questions', []));
    }
   // PTypes 

    
  
   // categoris
   
   public function categoris(Request $request){
        $cats = Servicecategory::paginate(10);
        $types = ServiceType::all();
        return view('page.Service.CategoryList')->with('cats', $cats)->with('types', $types);
   }


 function searchCats(Request $request) {

        $query = Servicecategory::query();

        if ($request->has('search') && $request->search) {
            $searchTerm = $request->search;

            $query->where('name', 'LIKE',"%{$searchTerm}%")
                ->orWhereHas('types', function ($q) use ($searchTerm) {
                    $q->where('name', 'LIKE', "%{$searchTerm}%");
                });
        }

        $cats = $query->paginate(15);
        $types = ServiceType::all();
        return view('page.Service.CategoryList')->with('cats', $cats)->with('types', $types);
    }

    public function addcategoris(Request $request){
        $request->validate([
            'type' => 'required|exists:service_types,id',
            'name' => 'required|min:5|max:40',
            'image' => 'required',

        ],
        [
            'type.required' => 'این فیلد الزامیست',
            'type.exists' => 'نوع اشتباه است',
            'name.required' => 'این فیلد الزامیست',
            'image.required' => 'این فیلد الزامیست',

        ]);

        $filename = time().'.'.$request->image->extension();
        $request->image->move(public_path('img'), $filename);

        $cat = new Servicecategory();
        $cat->name = $request->name;
	$cat->image = 'img/'.$filename;
	$cat->slogan = $request->slogan;
        $cat->service_type_id = $request->type;

        $cat->save();

        LogAdminAction::dispatch(
            auth('admin')->id(),
            'افزودن_دسته‌بندی',
            'دسته‌بندی '.$cat->name.' اضافه شد .',
            request()->ip(),
        );

        return redirect()->route('admin.categoris')->with('success', 'دسته‌بندی با موفقیت ایجاد شد');
    }

    public function deletecategoris(Request $request, $id){
        $cat = Servicecategory::find($id);

        $image_path = $cat->image;
        // Storage::delete('/'.$image_path);
        if (file_exists(public_path($image_path))) {

            FacadesFile::delete(public_path($image_path));
            
        }

        LogAdminAction::dispatch(
            auth('admin')->id(),
            'حذف_دسته‌بندی',
            'دسته‌بندی '.$cat->name.' حذف شد .',
            request()->ip(),
        );
        $cat->delete();
        return redirect()->route('admin.categoris')->with('success', 'دسته‌بندی با موفقیت حذف شد');
    }

    public function editcategoris($id){
        $cat = Servicecategory::find($id);
        $types = ServiceType::all();

        return view('page.Service.EditCategory')->with('cat', $cat)->with('types', $types);
    }

    public function updatecategoris(Request $request, $id){
        $request->validate([
            'type' => 'required|exists:service_types,id',
            'name' => 'required|min:5|max:40',
        ],
        [
            'type.required' => 'این فیلد الزامیست',
            'type.exists' => 'نوع اشتباه است',
            'name.required' => 'این فیلد الزامیست',

        ]);

        $cat  = Servicecategory::find($id);
        $image_path = $cat->image;

        if ($request->image != null) {
            $filename = time().'.'.$request->image->extension();
            $request->image->move(public_path('img'), $filename);

            if (file_exists(public_path($image_path))) {

                FacadesFile::delete(public_path($image_path));
                
            }

            $cat->update([
                'image' => "img/".$filename,
            ]);
        }

        $cat->update([
            'name'=>$request->name,
	    'service_type_id'=> $request->type,
	    'slogan' => $request->slogan
        ]);

        LogAdminAction::dispatch(
            auth('admin')->id(),
            'ویرایش_دسته‌بندی',
            'دسته‌بندی '.$cat->name.' ویرایش شد .',
            request()->ip(),
        );

        return redirect()->back()->with('success', 'دسته‌بندی با موفقیت ویرایش شد');
        
    }

    // type 
    public function types(Request $request){
        $types = ServiceType::paginate(5);
        return view('page.Service.TypeList')->with('types', $types);
    }
    
    public function searchType(Request $request) {

        $query = ServiceType::query();

        if ($request->has('search') && $request->search) {
            $searchTerm = $request->search;

            $query->where('name', 'LIKE',"%{$searchTerm}%")
                ;
        }

        $types = $query->paginate(15);
        return view('page.Service.TypeList')->with('types', $types);
    }
    public function addtypes(Request $request){
        $request->validate([
            'name' => 'required|min:2|max:40',
            'image' => 'required',

        ],
        [
            'name.required' => 'این فیلد الزامیست',
            'image.required' => 'این فیلد الزامیست',
        ]);

        $filename = time().'.'.$request->image->extension();
        $request->image->move(public_path('img'), $filename);

        $type = new ServiceType();
        $type->name = $request->name;
        $type->image = 'img/'.$filename;

        $type->save();

        LogAdminAction::dispatch(
            auth('admin')->id(),
            'افزودن_نوع',
            'نوع '.$type->name.' اضافه شد .',
            request()->ip(),
        );

        return redirect()->route('admin.types')->with('success', 'نوع با موفقیت ایجاد شد');
    }

    public function deletetypes(Request $request, $id){
        $type = ServiceType::find($id);
        $image_path = $type->image;
        if (file_exists(public_path($image_path))) {

            FacadesFile::delete(public_path($image_path));
            
        }

        LogAdminAction::dispatch(
            auth('admin')->id(),
            'حذف_نوع',
            'نوع '.$type->name.' حذف شد .',
            request()->ip(),
        );
        $type->delete();
        return redirect()->route('admin.types')->with('success', 'نوع با موفقیت حذف شد');
    }

    public function edittypes(Request $request, $id){
        $type = ServiceType::find($id);

        return view('page.Service.EditType')->with('type', $type);
    }

    public function updatetypes(Request $request, $id){
        $request->validate([
            'name' => 'required|min:2|max:40',
            // 'image' => 'required',

        ],
        [
            'name.required' => 'این فیلد الزامیست',
            // 'image.required' => 'این فیلد الزامیست',
        ]);

        $type = ServiceType::find($id);
        $image_path = $type->image;

        if ($request->image != null) {
            $filename = time().'.'.$request->image->extension();
            $request->image->move(public_path('img'), $filename);

            if (file_exists(public_path($image_path))) {

                FacadesFile::delete(public_path($image_path));
                
            }

            $type->update([
                'image' => "img/".$filename,
            ]);
        }

        $type->update([
            'name'=>$request->name,
        ]);

        LogAdminAction::dispatch(
            auth('admin')->id(),
            'ویرایش_نوع',
            'نوع '.$type->name.' ویرایش شد .',
            request()->ip(),
        );
        return redirect()->route('admin.types')->with('success', 'نوع با موفقیت ویرایش شد');
    }

    // users function
    public function users(){
        $users = User::orderBy('created_at', 'desc')->paginate(15);
        return view('page.UserManage.Users')->with('users', $users);
    }
    public function searchUser(Request $request) {

        $query = User::query();

        if ($request->has('search') && $request->search) {
            $searchTerm = $request->search;

            $query->where('name', 'LIKE',"%{$searchTerm}%")
                  ->orWhere('username', 'LIKE', "%{$searchTerm}")
                  ->orWhere('phone_number', 'LIKE', "%{$searchTerm}");
        }
        
        if ($status = request('status')) {
            $query->where('status', $status);
        }

        $users = $query->paginate(15);
        return view('page.UserManage.Users')->with('users', $users);

    }
    public function activedeactiveuser($id,Request $request){
        $user = User::find($id);

        if ($user->status == "pending") {

            LogAdminAction::dispatch(
                auth('admin')->id(),
                'ویرایش_کاربر',
                'کاربر '.$user->name.' فعال شد .',
                request()->ip(),
            );

            $user->update([
                'status'=>'active'
            ]);
        }
        else{
	$res=array($user->phone_number);
	if($request->has('custom_reason') && $request->has('reason')){
	if ($request->reason === 'دیگر') {
        $message = "کاربر گرامی ".$user->name ."  \n". " حساب کاربری شما به دلیل ". $request->custom_reason ." مسدود گردید . \n بروتوکار | borotokar.com";
    }
	 $message = "کاربر گرامی ".$user->name ."  \n". " حساب کاربری شما به دلیل ". $request->reason . " مسدود گردید . \n " . "توضیحات بیشتر :" .$request->custom_reason. "\nبروتوکار | borotokar.com";
	}else{
	        $message = "کاربر گرامی ".$user->name ."  \n". " حساب کاربری شما به دلیل نقص قوانین مسدود گردید . \n بروتوکار | borotokar.com";

	}

	 $result = Kavenegar::Send("9982001368", $res, $message);   
	 $user->update([
                'status'=>'pending'
            ]);   
        }
        LogAdminAction::dispatch(
            auth('admin')->id(),
            'ویرایش_کاربر',
            'کاربر '.$user->name.' غیرفعال شد .',
            request()->ip(),
        );
	 return back()->with('success', "وضعیت کاربر با موفقیت تغییر کرد" );
//	return $request->custom_reason ;
    }

    public function useredit($id)
    {
        $user = User::findOrFail($id);
        return view('page.UserManage.EditUser', compact('user'));
    }

    public function userupdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'phone_number' => 'required|string|max:255|unique:users,phone_number,' . $id,
            // 'picture' => 'nullable|string|max:255',
            'status' => 'required|in:pending,active',
            'sex' => 'in:Male,Fmale',
        ],[
            'name.required' => 'این فیلد الزامیست',
            'username.required' => 'این فیلد الزامیست',
            'phone_number.required' => 'این فیلد الزامیست',
            'status.required' => 'این فیلد الزامیست',
        ]);

        $user = User::findOrFail($id);
        $image_path = $user->picture;

        if ($request->image != null) {
            $filename = time().'.'.$request->image->extension();
            $request->image->move(public_path('img'), $filename);

            if (file_exists(public_path($image_path))) {

                FacadesFile::delete(public_path($image_path));
                
            }

            $user->update([
                'picture' => "img/".$filename,
            ]);
        }

        $user->update([
            'name' => $request->name,
            'username' => $request->username,
            'phone_number' => $request->phone_number,
            'status' => $request->status,
            'sex' => $request->sex
        ]);

        LogAdminAction::dispatch(
            auth('admin')->id(),
            'ویرایش_کاربر',
            'کاربر '.$user->name.' ویرایش شد .',
            request()->ip(),
        );

        return redirect()->route('admin.users')->with('success', 'کاربر با موفقیت ویرایش شد.');
    }

    public function userappsetting(Request $request){
        $setting = UserAppSetting::all()->first();
	//$allCategories = Servicecategory::all();
        if (empty($setting)) {
            $setting = new UserAppSetting();
            $setting->baneer1 = 'img/home.jpg';
            $setting->baneer2 = 'img/home.jpg';
            $setting->baneer3 = 'img/home.jpg';
            $setting->baneer4 = 'img/home.jpg';
            $setting->law = 'law';
	    $setting->save();

	}
    $experts = expert::all();
	$allCategories = Servicecategory::select('id', 'name')->get();
        return view('page.UserManage.UserAppSetting')->with('setting', $setting)->with('allCategories', $allCategories)->with('experts', $experts);
    }

    public function expertappsetting(Request $request){
        $setting = ExpertAppSetting::all()->first();
        if (empty($setting)) {
            $setting = new ExpertAppSetting();
            $setting->law = 'null';
            $setting->save();
        }
        return view('page.ExpertManage.ExpertAppSetting')->with('setting', $setting);
    }

    public function expertappsettingupdate(Request $request){
        $request->validate([
            'law' => 'required|string|max:1000000',
        ],[
            'law.required' => 'این فیلد الزامیست',
        ]);
        
        $setting = ExpertAppSetting::all()->first();
        if (empty($setting)) {
            $setting = new ExpertAppSetting();
            $setting->law = 'null';
            $setting->save();
        }

        $setting->update([
            "law"=>$request->law
        ]);
        LogAdminAction::dispatch(
            auth('admin')->id(),
            'ویرایش_تنظیمات_اپ_متخصص',
            'تنظیمات اپ متخصص ویرایش شد!',
            request()->ip(),
        );
        return redirect()->route('admin.expertappsetting')->with('success', 'تنظیمات اپ متخصص با موفقیت تغییر کرد');
   
    }

    public function userappsettingupdate(Request $request){
        $request->validate([
            'law' => 'required|string|max:100000',
            'categories' => 'nullable|array|max:15',
            'categories.*' => 'exists:servicecategories,id',

            'expert_id1' => 'nullable|exists:experts,id',
            'expert_id2' => 'nullable|exists:experts,id',
            'expert_id3' => 'nullable|exists:experts,id',
            'expert_id4' => 'nullable|exists:experts,id',

        ],[
            'law.required' => 'این فیلد الزامیست',
            'categories.max' => 'حداکثر 15 دسته‌بندی می‌توانید انتخاب کنید.',
            'expert_id1.exists' => 'متخصص اول نامعتبر است.',
            'expert_id2.exists' => 'متخصص دوم نامعتبر است.',
            'expert_id3.exists' => 'متخصص سوم نامعتبر است.',
            'expert_id4.exists' => 'متخصص چهارم نامعتبر است.',
        ]);

        $setting = UserAppSetting::all()->first();

        if (empty($setting)) {
            $setting = new UserAppSetting();
            $setting->baneer1 = 'img/home.jpg';
            $setting->baneer2 = 'img/home.jpg';
            $setting->baneer3 = 'img/home.jpg';
            $setting->baneer4 = 'img/home.jpg';
            $setting->law = 'law';
            $setting->save();
        }

        if ($request->baneer1 != null) {
        $filename = time().'.'.$request->baneer1->extension();
        $request->baneer1->move(public_path('img'), $filename);

            if (file_exists(public_path($setting->baneer1))) {

                FacadesFile::delete(public_path($setting->baneer1));
                
            }

            $setting->update([
                'baneer1' => "img/".$filename,
            ]);
        }

        if ($request->baneer2 != null) {
            $filename = time().'.'.$request->baneer2->extension();
            $request->baneer2->move(public_path('img'), $filename);

            if (file_exists(public_path($setting->baneer2))) {

                FacadesFile::delete(public_path($setting->baneer2));
                
            }

            $setting->update([
                'baneer2' => "img/".$filename,
            ]);
        }

        if ($request->baneer3 != null) {
            $filename = time().'.'.$request->baneer3->extension();
            $request->baneer3->move(public_path('img'), $filename);

            if (file_exists(public_path($setting->baneer3))) {

                FacadesFile::delete(public_path($setting->baneer3));
                
            }

            $setting->update([
                'baneer3' => "img/".$filename,
            ]);
        }

        if ($request->baneer4 != null) {
            $filename = time().'.'.$request->baneer4->extension();
            $request->baneer4->move(public_path('img'), $filename);

            if (file_exists(public_path($setting->baneer4))) {

                FacadesFile::delete(public_path($setting->baneer4));
                
            }

            $setting->update([
                'baneer4' => "img/".$filename,
            ]);
        }

        $setting->update([
            "law"=>$request->law,
	        'categories' => $request->categories,
             'expert_id1' => $request->expert_id1,
            'expert_id2' => $request->expert_id2,
            'expert_id3' => $request->expert_id3,
            'expert_id4' => $request->expert_id4,
        ]);

        LogAdminAction::dispatch(
            auth('admin')->id(),
            'ویرایش_تنظیمات_اپ_کاربر',
            'تنظیمات اپ کاربر ویرایش شد!',
            request()->ip(),
        );

        return redirect()->route('admin.userappsetting')->with('success', 'تنظیمات اپ کاربر با موفقیت تغییر کرد');
    }

    // experts 
    public function experts(){
        $experts = expert::where('is_active', 1)->orderBy('created_at','DESC')->paginate(15);
        $services = Service::all();
        return view('page.ExpertManage.Experts')->with('experts', $experts)->with('services', $services);
    }
    public function searchExpert(Request $request) {

        $query = expert::query();

        if ($request->has('search') && $request->search) {
            $searchTerm = $request->search;

	    $query
		  ->where('phone_number', 'LIKE',"%{$searchTerm}%")
                  ->orWhere('first_name', 'LIKE', "%{$searchTerm}")
                  ->orWhere('last_name', 'LIKE', "%{$searchTerm}")
                  ->orWhere('national_id', 'LIKE', "%{$searchTerm}")
                  ->orWhere('birth_date', 'LIKE', "%{$searchTerm}")
                  ->orWhere('type', 'LIKE', "%{$searchTerm}")
                  ->orWhere('city', 'LIKE', "%{$searchTerm}")
                  ->orWhereHas('services', function($query) use ($searchTerm) {
                    $query->where('title', 'LIKE', "%{$searchTerm}%");
                });
        }
        if ($request->badge == 'blue') {
            $query->where('blue_tick', 1); // فرض کردم تیک آبی = badge ستونش 1 هست
        }
        $experts = $query->where('is_active', 1)->paginate(15);
        $services = Service::all();
        return view('page.ExpertManage.Experts')->with('experts', $experts)->with('services', $services);
    }

     public function searchExpertN(Request $request) {

        $query = expert::query();

        if ($request->has('search') && $request->search) {
            $searchTerm = $request->search;

            $query
                  ->where('phone_number', 'LIKE',"%{$searchTerm}%")
                  ->orWhere('first_name', 'LIKE', "%{$searchTerm}")
                  ->orWhere('last_name', 'LIKE', "%{$searchTerm}")
                  ->orWhere('national_id', 'LIKE', "%{$searchTerm}")
                  ->orWhere('birth_date', 'LIKE', "%{$searchTerm}")
                  ->orWhere('type', 'LIKE', "%{$searchTerm}")
                  ->orWhere('city', 'LIKE', "%{$searchTerm}")
                  ->orWhereHas('services', function($query) use ($searchTerm) {
                    $query->where('title', 'LIKE', "%{$searchTerm}%");
                })
                  ;
        }

        $experts = $query->where('is_active', 0)->get();
        $services = Service::all();
        return view('page.ExpertManage.ExpertsAccessList')->with('experts', $experts)->with('services', $services);
    }
    public function addexpert(Request $request){
        $request->validate([
            'phone_number' => 'required|string|unique:experts',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'national_id' => 'required|string|unique:experts',
            'birth_date' => 'required|date',
            'type' => 'required|in:business_unit,self_employed,company',
            'telegram_link' => 'nullable|string',
            'whatsapp_link' => 'nullable|string',
            'eitaa_link' => 'nullable|string',
            'address' => 'required|string',
            'province' => 'required|string',
            'city' => 'required|string',
            'is_active' => 'boolean',
            'services' => 'array',
	    'services.*' => 'exists:services,id',
	    'website_link' => 'nullable|string',
	    'about_me' => 'required|min:5|max:1000',
	    'guarantee' => 'required|min:5|max:1000',
            // other document validations
        ],
        [
            'phone_number.required' => 'این یک فیلد الزامیست',
            'phone_number.unique' => 'این شماره از قبل ثبت شده',
            'first_name.required' => 'این یک فیلد الزامیست',
            'last_name.required' => 'این یک فیلد الزامیست',
            'national_id.required' => 'این یک فیلد الزامیست',
            'national_id.unique' => 'این کدملی از قبل ثبت شده',
            'type.required' => 'این یک فیلد الزامیست',
            'address.required' => 'این یک فیلد الزامیست',
            'province.required' => 'این یک فیلد الزامیست',
            'birth_date.required' => 'این یک فیلد الزامیست',
            'city.required' => 'این یک فیلد الزامیست',
            'is_active.boolean' => 'is not bool',
            'services.array' => 'خدمات باید بصورت آرایه باشند',
            'services.*.exists' => 'خدمات باید بصورت موجود باشند',

            
        ]
    );

        // $expert = expert::create($request->except(['services', 'documents', 'gallery']));
        $profile_image = time().'.p.'.$request->profile_image->extension();
        $request->profile_image->move(public_path('img'), $profile_image);

        $expert = expert::create([
            'phone_number' => $request->phone_number,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'national_id' => $request->national_id,
            'birth_date' => $request->birth_date,
            'type' => $request->type,
            'address' => $request->address,
            'province' => $request->province,
            'city' => $request->city,
            'lat'=>$request->lat,
            'log'=>$request->log,
            'is_active' => $request->is_active,
            'telegram_link' => $request->telegram_link,
            'whatsapp_link' => $request->whatsapp_link,
            'eitaa_link' => $request->eitaa_link,
	    'profile_image'=>'img/'.$profile_image,
	    'website_link' => $request->website_link,
	    'about_me' => $request->about_me,
	    'guarantee' => $request->guarantee,
        ]);

        if ($expert->type == "company") {
            $expert->company_name = $request->company_name;
            $expert->registration_number = $request->registration_number;
        }

        if ($request->has('services')) {
            $expert->services()->sync($request->services);
        }

        if ($request->hasFile('documents')) {
            $documents = [];
            foreach ($request->file('documents') as $key => $file) {
                $filename = time().'.d.'.$file->extension();
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

        if ($request->hasFile('gallery')) {
            $gallery = [];
            foreach ($request->file('gallery') as $key => $file) {
                // $path = $file->store('gallery', 'public');
                $filename = time().'.g.'.$file->extension();
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

        $wallet = new Wallet(['balance' => 0]); // موجودی اولیه کیف پول 0
	    $expert->wallet()->save($wallet);
        $expert->save();

        LogAdminAction::dispatch(
            auth('admin')->id(),
            'افزودن_متخصص',
            'متخصص با نام '. $expert->first_name .' '. $expert->last_name .' اضافه شد .',
            request()->ip(),
        );

        return redirect()->route('admin.experts')->with('success', 'متخصص با موفقیت اضافه شد.');
        // return response($expert);
    }

    public function editexpert($id){
        $expert = expert::find($id);
        $services = Service::all();
        return view('page.ExpertManage.EditExpert')->with('expert', $expert)->with('services', $services);
    }

    public function upadetexpert(Request $request, $id){
        
        $expert = expert::find($id);
        $request->validate([
            'phone_number' => 'required|string',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'national_id' => 'required|string',
            'birth_date' => 'required|date',
            'type' => 'required|in:business_unit,self_employed,company',
            'telegram_link' => 'nullable|string',
            'whatsapp_link' => 'nullable|string',
            'eitaa_link' => 'nullable|string',
            'address' => 'required|string',
            'province' => 'required|string',
            'city' => 'required|string',
	    'is_active' => 'boolean',
	    'blue_tick' => 'boolean',
	    'website_link' => 'nullable|string',
	    'about_me' => 'required|min:5|max:1000',
	    'guarantee' => 'required|min:5|max:1000',
            // 'services' => 'array',
            // 'services.*' => 'exists:services,id',
            // other document validations
        ],
        [
            'phone_number.required' => 'این یک فیلد الزامیست',
            'first_name.required' => 'این یک فیلد الزامیست',
            'last_name.required' => 'این یک فیلد الزامیست',
            'national_id.required' => 'این یک فیلد الزامیست',
            'type.required' => 'این یک فیلد الزامیست',
            'address.required' => 'این یک فیلد الزامیست',
            'province.required' => 'این یک فیلد الزامیست',
            'birth_date.required' => 'این یک فیلد الزامیست',
            'city.required' => 'این یک فیلد الزامیست',
            // 'is_active.boolean' => 'is not bool',
            // 'services.array' => 'خدمات باید بصورت آرایه باشند',
            // 'services.*.exists' => 'خدمات باید بصورت موجود باشند',

            
        ]
    );

    if ($request->profile_image != null) {
        $profile_image = time().'.p.'.$request->profile_image->extension();
        $request->profile_image->move(public_path('img'), $profile_image);
        $expert->update([
            'profile_image'=>'img/'.$profile_image
        ]);
    }

    $expert->update([
        'phone_number' => $request->phone_number,
        'first_name' => $request->first_name,
        'last_name' => $request->last_name,
        'national_id' => $request->national_id,
        'birth_date' => $request->birth_date,
        'type' => $request->type,
        'address' => $request->address,
        'province' => $request->province,
        'city' => $request->city,
        'is_active' => $request->is_active,
        'telegram_link' => $request->telegram_link,
        'whatsapp_link' => $request->whatsapp_link,
	'eitaa_link' => $request->eitaa_link,
	'website_link' => $request->website_link,
	'about_me' => $request->about_me,
	'guarantee' => $request->guarantee,
	'blue_tick' => $request->blue_tick
    ]);

    if ($expert->type == "company") {
        $expert->company_name = $request->company_name;
        $expert->registration_number = $request->registration_number;
    }

    if ($request->has('services')) {
        $expert->services()->sync($request->services);
    }

    if ($request->hasFile('documents')) {
        $documents = [];
        foreach ($request->file('documents') as $key => $file) {
            $filename = time().$key.'.d.'.$file->extension();
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

    if ($request->hasFile('gallery')) {
        $gallery = [];
        foreach ($request->file('gallery') as $key => $file) {
            // $path = $file->store('gallery', 'public');
            $filename = time().'.g.'.$file->extension();
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

        LogAdminAction::dispatch(
            auth('admin')->id(),
            'ویرایش_متخصص',
            'متخصص با نام '. $expert->first_name .' '. $expert->last_name .' ویرایش شد .',
            request()->ip(),
        );
        return redirect()->route('admin.experts')->with('success', 'متخصص با موفقیت ویرایش شد.');
        // return response($documents);
    }

    public function expertsaccesslist(){
        $experts = expert::where('is_active', 0)->has('documents')->get();
        $services = Service::all();
        return view('page.ExpertManage.ExpertsAccessList')->with('experts', $experts);
    }

    public function expertRejectList(){
        $experts = expert::where('is_active', 0)->doesntHave('documents')->get();
        $services = Service::all();
        return view('page.ExpertManage.ExpertsRejecList')->with('experts', $experts);
    }

    public function expertsaccess($id){
        $expert = expert::find($id);
        // $expert->
        return view('page.ExpertManage.ExpertsAccess')->with('expert', $expert);
    }

    public function expertaccesshandller(Request $request ,$id){
        $expert = expert::find($id);
	
        if ($request->status == "active") {
            $expert->update([
                'is_active' => 1
            ]);

            try{
            //    $api = new KavenegarApi( "445A693566472F757349713846345544735933486F6A59506E4E415775374B5632684E415956584B464B413D" );
	    
	        $res = array($expert->phone_number);
            
            $receptor = $expert->phone_number;
            $token = preg_replace('/\s+/', '', $expert->first_name);
            $token2 = preg_replace('/\s+/', '', $expert->last_name);
            
            // با سلام
            // آقا/خانم %token %token2 مدارک هویتی شما تایید شد . 
            // بروتوکار | borotokar.com

            // $message = "با سلام \n". "آقا / خانم ". $token." ". $token2 . . "" ." مدارک هویتی شما تایید شد  \n بروتوکار | borotokar.com";
            $message = "با سلام\n"
                . "آقا / خانم " . $token . " " . $token2 . "\n"
                . "مدارک هویتی شما با موفقیت تایید شد ✅\n\n"
                . "به خانواده متخصصین بروتوکار خوش آمدید! 🌟\n"
                . "از این لحظه، می‌تونید سفارش‌های متناسب با تخصص‌تون رو دریافت و مدیریت کنید.\n"
                . "برای شروع، وارد اپلیکیشن بشید و فرصت‌های جدید رو از دست ندید.\n\n"
                . "با آرزوی موفقیت \n"
                . "بروتوکار | borotokar.com";
            $result = Kavenegar::Send("9982001368", $res, $message);   
            
            // $token3 = null;
		    // $template="access-expert";
	        // $result = Kavenegar::VerifyLookup($receptor, $token, $token2, $token3, $template, $type = null); 
            
            LogAdminAction::dispatch(
                auth('admin')->id(),
                'تایید_متخصص',
                'متخصص با نام '. $expert->first_name .' '. $expert->last_name .' تایید شد .',
                request()->ip(),
            );
		    return redirect()->route('admin.expertsaccesslist')->with('success', 'متخصص با موفقیت تایید شد.');

                }
                catch(\Kavenegar\Exceptions\ApiException $e){
                    // در صورتی که خروجی وب سرویس 200 نباشد این خطا رخ می دهد
               	 $erore = $e->errorMessage();
                 return redirect()->route('admin.expertsaccesslist')->with('success', $erore);

               }
            
               catch(\Kavenegar\Exceptions\HttpException $e){
                    // در زمانی که مشکلی در برقرای ارتباط با وب سرویس وجود داشته باشد این خطا رخ می دهد
                    $erore = $e->errorMessage();
                    return redirect()->route('admin.expertsaccesslist')->with('success', $erore);

               }

            
        }
        if($request->status == "notactive"){
            try{
                //$api = new KavenegarApi( "445A693566472F757349713846345544735933486F6A59506E4E415775374B5632684E415956584B464B413D" );
                $res = array($expert->phone_number);
            
                $receptor = $expert->phone_number;
                $token = $expert->first_name;
		$token2 = $expert->last_name;
                $token3 = null;
		// $template="cancle-expert";
		switch ($request->reason) {
                    case 1:
                        $template="dc";
                        $msg = " آقا / خانم " . $token . " ". $token2 . " مدارک هویتی شما به دلیل نقص مدارک هویتی رد  شد .";
                    break;
                    case 2:
                        $template="ec";
                        $msg = " آقا / خانم " . $token . " " .$token2 . " مدارک هویتی شما به دلیل نقص مدارک مربوط به تخصص رد  شد .";
                    break;
                    case 3:
                        $template="vc";
                        $msg = " آقا / خانم " . $token . " " .$token2 . " مدارک هویتی شما به دلیل نقص در ویدئو احراز هویت  رد شد .";
                    break;
                    case 4:
                        $template="eec";
                        $msg = " آقا / خانم " . $token . " " .$token2 . " مدارک هویتی شما ثبت تخصص های غیر مرتبط رد  شد .";
                    break;
                    case 5:
                        $template="ac";
                        $msg = " آقا / خانم " . $token . " ". $token2 . " مدارک هویتی شما به دلیل ثبت ناقص آدرس رد شد .";
                    break;
                                            
                    default:
                        $template="cancle-expert";
                        $msg = " آقا / خانم " . $token . " ". $token2 . " مدارک هویتی شما رد شد .";
                    break;
                }
                $des = "دلیل رد شدن : " . $request->des;
                $message = $msg . "\n" . $des . "\n" . "بروتوکار | borotokar.com";
                //$result = $api->VerifyLookup($receptor, $token, $token2, $token3, $template, $type = null);
		//$result = Kavenegar::VerifyLookup($receptor, $token, $token2, $token3, $template);
		$result = Kavenegar::Send("9982001368",$res , $message);
		//$docs = $experts->d
		foreach($expert->documents as $key => $document){
			$document->delete();
		}
		if($expert->video){
			$expert->video->delete();
		}
		$expert->save();

        LogAdminAction::dispatch(
            auth('admin')->id(),
            'رد_متخصص',
            'متخصص با نام '. $expert->first_name .' '. $expert->last_name .' رد شد .',
            request()->ip(),
        );

		return redirect()->route('admin.expertsaccesslist')->with('success', 'متخصص با موفقیت رد شد.');

                }
                catch(\Kavenegar\Exceptions\ApiException $e){
                    // در صورتی که خروجی وب سرویس 200 نباشد این خطا رخ می دهد
                    $erore = $e->errorMessage();
                    return redirect()->route('admin.expertsaccesslist')->with('success', $erore);

                }
            
               catch(\Kavenegar\Exceptions\HttpException $e){
                    // در زمانی که مشکلی در برقرای ارتباط با وب سرویس وجود داشته باشد این خطا رخ می دهد
                    $erore = $e->errorMessage();
                    return redirect()->route('admin.expertsaccesslist')->with('success', $erore);

                }
        }

        return redirect()->route('admin.expertsaccesslist')->with('success', 'متخصص با موفقیت ویرایش شد.');
    }

    public function activedeactiveexpert($id){
        $user = expert::find($id);

        if ($user->is_active) {
            LogAdminAction::dispatch(
                auth('admin')->id(),
                'غیرفعال_کردن_متخصص',
                'متخصص با نام '. $user->first_name .' '. $user->last_name .' غیرفعال شد .',
                request()->ip(),
            );
            $user->update([
                'is_active'=> 0
            ]);
        }
        else{
            LogAdminAction::dispatch(
                auth('admin')->id(),
                'فعال_کردن_متخصص',
                'متخصص با نام '. $user->first_name .' '. $user->last_name .' فعال شد .',
                request()->ip(),
            );
            $user->update([
                'is_active'=> 1
            ]);   
        }
        return redirect()->route('admin.experts')->with('success', 'وضعیت متخصص با موفقیت تغییر کرد');
    }

    public function orders(){
        $orders = Order::orderBy('created_at', 'desc')->paginate(15);
        return view('page.order.OrderList')->with('orders', $orders);
    }


    public function order($id){
        $order = Order::find($id);
        $order->load('bids', 'answers');
        return view('page.order.Order')->with('order', $order);
    }
public function searchOrder(Request $request) {

        $query = Order::query();

        if ($request->has('search') && $request->search) {
            $searchTerm = $request->search;

            $query
                 ->where('created_at', 'LIKE',"%{$searchTerm}%")
                  ->orWhereHas('user', function ($q) use ($searchTerm) {
                    $q->where('name', 'LIKE', "%{$searchTerm}%");
                    })
                    ->orWhereHas('service', function ($q) use ($searchTerm) {
                        $q->where('title', 'LIKE', "%{$searchTerm}%");
                    })
                  ->orWhere('city', 'LIKE', "%{$searchTerm}")
                  ->orWhere('completion_date', 'LIKE', "%{$searchTerm}");
        }

        $orders = $query->paginate(15);
        return view('page.order.OrderList')->with('orders', $orders);
   }

    public function deleteorder($id){
        $order = Order::find($id);
        LogAdminAction::dispatch(
                auth('admin')->id(),
                'حذف_سفارش',
                ' سفارش ' . $order->service->title. ' کاربر '. $order->user->name . ' حذف شد.' ,
                request()->ip(),
        );
        $order->delete();
        return redirect()->route('admin.orders')->with('success', 'سفارش با موفقیت حذف شد');
    }

    public function reviews(){
        $reviews = Review::latest()->get();
        return view('page.ExpertManage.ReviewList')->with('reviews', $reviews);
    }
    public function searchReviews(Request $request) {

        $query = Review::query();

        if ($request->has('search') && $request->search) {
            $searchTerm = $request->search;

            $query
                  
                  ->whereHas('user', function ($q) use ($searchTerm) {
                    $q->where('name', 'LIKE', "%{$searchTerm}%");
                    })
                    ->orWhereHas('expert', function ($q) use ($searchTerm) {
                        $q->where('first_name', 'LIKE', "%{$searchTerm}%");
                    });
        }

        $reviews = $query->paginate(15);
        return view('page.ExpertManage.ReviewList')->with('reviews', $reviews);
   } 
    public function accessreview($id){
        $review = Review::find($id);
        if ($review->is_active) {
            LogAdminAction::dispatch(
                auth('admin')->id(),
                'غیرفعال_کردن_نظر',
                ' نظر  کاربر '. $review->user->name .' برای متخصص '. $review->expert->first_name . ' '. $review->expert->last_name .' غیرفعال شد.' ,
                request()->ip(),
            );
            $review->update([
                'is_active'=> false
            ]);
        }else{
            LogAdminAction::dispatch(
                auth('admin')->id(),
                'فعال_کردن_نظر',
                ' نظر  کاربر '. $review->user->name .' برای متخصص '. $review->expert->first_name . ' '. $review->expert->last_name .' فعال شد.' ,
                request()->ip(),
            );
            $review->update([
                'is_active'=> true
            ]);
        }
        return redirect()->route('admin.reviews')->with('success', 'با موفقیت وعضیت تغییر کرد');
    }

    public function deletereview($id){
        $review = Review::find($id);
        LogAdminAction::dispatch(
                auth('admin')->id(),
                'حذف_کردن_نظر',
                ' نظر  کاربر '. $review->user->name .' برای متخصص '. $review->expert->first_name . ' '. $review->expert->last_name .' حذف شد.' ,
                request()->ip(),
        );
        $review->delete();
        return redirect()->route('admin.reviews')->with('success', 'با موفقیت  حذف شد');
    }

    public function transactions(Request $request){
        $transactions = Transaction::where('status', "completed" )->orderBy('created_at','DESC')->get();
        return view('page.Suport.transactions')->with('transactions', $transactions);

    }

        public function searchTransactions(Request $request) {

        $query = Transaction::query();

        if ($request->has('search') && $request->search) {
            $searchTerm = $request->search;

            $query
                  
                  ->where('transaction_id', 'LIKE', "%{$searchTerm}")
                  ->orWhereHas('expert', function ($q) use ($searchTerm) {
                    $q->where('first_name', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('first_name', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('phone_number', 'LIKE',"%{$searchTerm}%");
                    });
        }

        $transactions = $query->where('status', 'completed')->paginate(15);
        return view('page.Suport.transactions')->with('transactions', $transactions);
   }
   
     public function expertSendMessage()
   {
       $users = expert::where('is_active', 1)->orderBy('created_at','DESC')->get(); 
       return view('page.Suport.emsg', compact('users'));
   }

   public function expertSendMessage_handller(Request $request)
   {
       // اعتبارسنجی
       $request->validate([
          'message' => 'required|string',
          'user_id' => 'nullable|array',
       ]);

       // ذخیره نوتیفیکیشن برای یک یا چند کاربر
       $isName = $request->input('name', 1);
       // ﺬﺨﯾﺮﻫ ﻥﻮﺘﯿﻔﯿﮑﯿﺸﻧ ﺏﺭﺎﯾ ﯽﮐ ﯼﺍ ﭻﻧﺩ ﮎﺍﺮﺑﺭ
       if ($request->user_id) {
           //$res = array();
           $message = $request->message;
           foreach ($request->user_id as $userId) {
               $user = expert::find($userId);
               $res = array($user->phone_number);
               //array_push($res, $user->phone_number);
               if ($isName == 0){
                       $add = "سلام ".$user->first_name . " ". $user->last_name. " عزیز \n";
                       $result = Kavenegar::Send("9982001368", $res, $add.$message);
               }else{
               $result = Kavenegar::Send("9982001368", $res, $message);
               }

            LogAdminAction::dispatch(
                auth('admin')->id(),
                'ارسال_پیام_متخصص',
                'پیام '. $message . ' به متخصص ' . $user->first_name . ' '. $user->last_name. 'ارسال شد .' ,
                request()->ip(),
                );
            }
           //$message = $request->message;
           //$result = Kavenegar::Send("9982001368", $res, $message);
       } else {
           // ﺍﺮﺳﺎﻟ ﺐﻫ ﻪﻤﻫ ﮎﺍﺮﺑﺭﺎﻧ
           $users = expert::all();
           $message = $request->message;
           //$res = array();
           foreach ($users as $user) {
               //array_push($res, $user->phone_number);
               $res = array($user->phone_number);
               //array_push($res, $user->phone_number);
               if ($isName == 0){
                        $add = "سلام ".$user->first_name . " ". $user->last_name. " عزیز \n";
                       $result = Kavenegar::Send("9982001368", $res, $add.$message);
               }else{
               $result = Kavenegar::Send("9982001368", $res, $message);
               }
           }

            LogAdminAction::dispatch(
                auth('admin')->id(),
                'ارسال_پیام_متخصص',
                'پیام '. $message . ' به همه ی متخصصین ارسال کرد',
                request()->ip(),
            );
       }

       return redirect()->route('admin.emsg')->with('success', 'پیام ها با موفقیت ارسال شد');
   }

   public function userSendMessage()
   {
       $users = User::all(); 
       return view('page.Suport.msg', compact('users'));
   }

   public function userSendMessage_handller(Request $request)
    {
        // اعتبارسنجی
        $request->validate([
           'message' => 'required|string',
           'user_id' => 'nullable|array',
        ]);
	$isName = $request->input('name', 1);
        // ذخیره نوتیفیکیشن برای یک یا چند کاربر
        if ($request->user_id) {
            //$res = array();
	    $message = $request->message;	
	    foreach ($request->user_id as $userId) {
                $user = User::find($userId);
                $res = array($user->phone_number);
                //array_push($res, $user->phone_number);
                if ($isName == 0){
                    $add = "سلام ".$user->name. " عزیز \n";
                    $result = Kavenegar::Send("9982001368", $res, $add.$message);
                }else{
                $result = Kavenegar::Send("9982001368", $res, $message);
                }	

            LogAdminAction::dispatch(
                auth('admin')->id(),
                'ارسال_پیام_کاربر',
                'پیام '. $message . ' به کاربر ' . $user->name . ' ارسال شد .' ,
                request()->ip(),
            );
	     }
            //$message = $request->message;
            //$result = Kavenegar::Send("9982001368", $res, $message);
        } else {
            // ارسال به همه کاربران
            $users = User::all();
	    $message = $request->message;
	    //$res = array();
            foreach ($users as $user) {
                //array_push($res, $user->phone_number);
	        $res = array($user->phone_number);
                //array_push($res, $user->phone_number);
                if ($isName == 0){
                        $add = "ﺱﻼﻣ ".$user->name. " ﻉﺰﯾﺯ \n";
                        $result = Kavenegar::Send("9982001368", $res, $add.$message);
                }else{
                $result = Kavenegar::Send("9982001368", $res, $message);
                } 
	    }
            LogAdminAction::dispatch(
                auth('admin')->id(),
                'ارسال_پیام_کاربران',
                'پیام '. $message . ' به همه ی کاربر ها ارسال کرد',
                request()->ip(),
            );
            //$message = $request->message;
            //$result = Kavenegar::Send("9982001368", $res, $message);
        }

        return redirect()->route('admin.msg')->with('success', 'پیام ها با موفقیت ارسال شد');
    }
    
    public function enotifications()
    {
        $notifications = ExpertNotification::select('title', 'message')
        ->selectRaw('count(*) as total, min(id) as id')
        ->groupBy('title', 'message')
        ->get();
        $experts = expert::all(); // کاربران برای ارسال نوتیف

        return view('page.Suport.enotif', compact('notifications', 'experts'));
    }

    public function eaddnotifications(Request $request)
    {
        // اعتبارسنجی
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'expert_id' => 'nullable|array',
        ]);

        $fcm = new ExpertFirebaseNotificationService();

        // ذخیره نوتیفیکیشن برای یک یا چند کاربر
        if ($request->expert_id) {
            foreach ($request->expert_id as $userId) {
                $user = expert::find($userId);
                ExpertNotification::create([
                    'title' => $request->title,
                    'message' => $request->message,
                    'expert_id' => $userId,
                ]);
                    
                if (!is_null($user->fcm_token)) {
                    $fcm->send(
                        $user->fcm_token,
                        $request->title,
                        Str::limit($request->message, 50),
                        ['type' => 'notif']
                    );
                }

                LogAdminAction::dispatch(
                auth('admin')->id(),
                'ارسال_پیام_متخصص',
                'پیام '. $request->message . ' به متخصص ' . $user->first_name . ' '. $user->last_name. 'ارسال شد .' ,
                request()->ip(),
                );
            }
        } else {
            // ارسال به همه کاربران
            $users = expert::all();
            foreach ($users as $user) {
                ExpertNotification::create([
                    'title' => $request->title,
                    'message' => $request->message,
                    'expert_id' => $user->id,
                ]);

                if (!is_null($user->fcm_token)) {
                    $fcm->send(
                        $user->fcm_token,
                        $request->title,
                        Str::limit($request->message, 50),
                        ['type' => 'notif']
                    );
                }
            }

            LogAdminAction::dispatch(
                auth('admin')->id(),
                'ارسال_نوتیف_متخصص',
                'نوتیف '. $request->message . ' به همه ی متخصصین ارسال کرد',
                request()->ip(),
            );
        }

        return redirect()->route('admin.enotif')->with('success', 'اعلان با موفقیت ارسال شد');
    }

    public function edeletenotifications($id)
    {
        $notification =  ExpertNotification::find($id);
        $notifications = ExpertNotification::where('title', $notification->title)->where('message', $notification->message)->delete();
        return redirect()->route('admin.enotif')->with('success', 'اعلان با موفقیت پاک شد');
    }

    
    public function notifications()
    {
        $notifications = UserNotification::select('title', 'message')
        ->selectRaw('count(*) as total, min(id) as id')
        ->groupBy('title', 'message')
        ->get();
        $users = User::all(); // کاربران برای ارسال نوتیف

        return view('page.Suport.notif', compact('notifications', 'users'));
    }

    public function addnotifications(Request $request)
    {
        // اعتبارسنجی
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'user_id' => 'nullable|array',
        ]);

        $fcm = new FirebaseNotificationService();


        // ذخیره نوتیفیکیشن برای یک یا چند کاربر
        if ($request->user_id) {
            foreach ($request->user_id as $userId) {
                $user = User::find($userId);
                UserNotification::create([
                    'title' => $request->title,
                    'message' => $request->message,
                    'user_id' => $userId,
                ]);

                LogAdminAction::dispatch(
                auth('admin')->id(),
                'ارسال_نوتیف_کاربر',
                'نوتیف '. $request->message . ' به کاربر ' . $user->name . 'ارسال شد .' ,
                request()->ip(),
                );

                try {
                    if (!is_null($user->fcm_token)) {
                        $fcm->send(
                            $user->fcm_token,
                            $request->title,
                            Str::limit($request->message, 50),
                            ['type' => 'notif']
                        );
                    }
                } catch (\Throwable $th) {
                    //throw $th;
                }
            }
        } else {
            // ارسال به همه کاربران
            $users = User::all();
            foreach ($users as $user) {
                UserNotification::create([
                    'title' => $request->title,
                    'message' => $request->message,
                    'user_id' => $user->id,
                ]);

                try {
                    if (!is_null($user->fcm_token)) {
                        $fcm->send(
                            $user->fcm_token,
                            $request->title,
                            Str::limit($request->message, 50),
                            ['type' => 'notif']
                        );
                    }
                } catch (\Throwable $th) {
                    //throw $th;
                }
            }

            LogAdminAction::dispatch(
                auth('admin')->id(),
                'ارسال_نوتیف_کاربران',
                'نوتیف '. $request->message . ' به همه ی کاربران ارسال کرد',
                request()->ip(),
            );
        }

        return redirect()->route('admin.notif')->with('success', 'اعلان با موفقیت ارسال شد');
    }

    public function deletenotifications($id)
    {
        $notification =  UserNotification::find($id);
        $notifications = UserNotification::where('title', $notification->title)->where('message', $notification->message)->delete();
        return redirect()->route('admin.notif')->with('success', 'اعلان با موفقیت پاک شد');
    }

    
    public function wallets(Request $request){
        $wallets = Wallet::all();
        return view('page.ExpertManage.Wallets', compact('wallets'));
    }
    public function searchWallet(Request $request) {

        $query = Wallet::query();

        if ($request->has('search') && $request->search) {
            $searchTerm = $request->search;

            $query
                  
                  ->whereHas('expert', function ($q) use ($searchTerm) {
                    $q->where('first_name', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('first_name', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('phone_number', 'LIKE',"%{$searchTerm}%")
                      ->orWhere('national_id', 'LIKE',"%{$searchTerm}%");
                    });
        }

        $wallets = $query->paginate(15);
        return view('page.ExpertManage.Wallets', compact('wallets'));
   }

    public function walletEdit($id){
        $wallet = Wallet::find($id);
        return view('page.ExpertManage.WalletEdit', compact('wallet'));
    }

    public function walletEditHandller(Request $request, $id){
        $request->validate([
            "balance" => 'required'
        ]);
        $wallet = Wallet::find($id);
        if ($request->balance != $wallet->balance) {
           $wallet->balance = $request->balance;
           $wallet->save();

           
            LogAdminAction::dispatch(
                auth('admin')->id(),
                'افزایش_موجودی_کیف_پول',
                'موجودی کیف پول ' . $wallet->expert->first_name. ' '. $wallet->expert->last_name. ' به ' . $request->balance . 'تغییر کرد .',
                request()->ip(),
            );
        }

        return redirect()->route('admin.wallets')->with('success', 'ویرایش با موفقیت انجام شد');

    }

    public function nindex(){
			        $notifications = Notification::orderBy('created_at', 'desc')->get();
				        return view('page.notif', compact('notifications'));
    }
    public function markAsRead($id)
    {
	        $notification = Notification::find($id);
		$notification->delete();
		

	        return  redirect()->route('admin.home')->with('success', 'نوتیفیکیشن خوانده شد.');
    }

   public function chats(){
	   $conversations = Conversation::with(['user', 'expert'])->withTrashed()->latest()->paginate(15);
	   return view('page.Suport.chatsList', compact('conversations'));
   }

    public function showChats($id)
    {
        $conversation = Conversation::withTrashed()->findOrFail($id);
        return view('page.Suport.chat', compact('conversation'));
    }

    public function expertBlueTickRequest(){
        $experts = expert::where('is_blue_tick_request', 1)->orderBy('created_at','DESC')->paginate(15);
        return view('page.ExpertManage.ExpertsBlue')->with('experts', $experts);
    }

    public function setExpertBlueTick(Request $request, $id){
        $expert = expert::find($id);
        $expert->update([
            'is_blue_tick_request' => 0,
            'blue_tick' => 1
        ]);

        if ($expert->notification) {
            try {
                $fcm = new ExpertFirebaseNotificationService();
                if (!is_null($expert->fcm_token)) {
                        $fcm->send(
                            $expert->fcm_token,
                            "پشتیبانی بروتوکار",
                            "متخصص گرامی حساب کاربر شما به مرحله حرفه ای ارتقا یافت و نشان تیک آبی را دریافت نمودید",
                            ['type' => 'notif']
                        );
                }
            } catch (\Throwable $th) {
                
            }
        }

        if ($expert->sms_notification) {
            try {
                $res = array($expert->phone_number);
                $message =  " متخصص گرامی حساب کاربر شما به مرحله حرفه ای ارتقا یافت و نشان تیک آبی را دریافت نمودید\nبروتوکار | borotokar.com" ;
                $result = Kavenegar::Send("9982001368", $res, $message);   
            } catch (\Throwable $th) {
            }
        }

        LogAdminAction::dispatch(
            auth('admin')->id(),
            'اضافه_کردن_تیک_آبی',
            ' تیک آبی متخصص '. $expert->first_name . $expert->last_name . ' اضافه شده.',
            request()->ip(),
        );

        return redirect()->route('admin.expertsBlue')->with('success', 'متخصص تیک آّبی گرفت!');
    }

    public function unsetExpertBlueTick(Request $request, $id){
        $expert = expert::find($id);
        $expert->update([
            'is_blue_tick_request' => 0,
            'blue_tick' => 0
        ]);

        if ($expert->notification) {
            try {
                $fcm = new ExpertFirebaseNotificationService();
                if (!is_null($expert->fcm_token)) {
                        $fcm->send(
                            $expert->fcm_token,
                            "پشتیبانی بروتوکار",
                            "متخصص گرامی تیک آبی حساب شما غیر فعال شد !",
                            ['type' => 'notif']
                        );
                }
            } catch (\Throwable $th) {
                
            }
        }

        if ($expert->sms_notification) {
            try {
            $res = array($expert->phone_number);
            $message =  "متخصص گرامی تیک آبی حساب شما غیر فعال شد !\nبروتوکار | borotokar.com" ;
            $result = Kavenegar::Send("9982001368", $res, $message);   
            } catch (\Throwable $th) {
            }
        }

        LogAdminAction::dispatch(
            auth('admin')->id(),
            'برداشتن_تیک_آبی',
            ' تیک آبی متخصص '. $expert->first_name . $expert->last_name . ' برداشته شده.',
            request()->ip(),
        );
        return redirect()->route('admin.experts')->with('success', 'تیک آبی متخصص برداشته شد !');
    }


    public function createAdmin()
    {
        $roles = Role::all();
        return view('page.admin.create', compact('roles'));
    }

    public function storeAdmin(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'username'    => 'required|string|max:255|unique:admins',
            'phone_number'=> 'required|string|max:20',
            'password'    => 'required|string|min:6',
            'picture'     => 'nullable|image|max:2048',
            'roles'       => 'array'
        ]);

        $data = $request->only(['name','username','phone_number']);

        // اگر عکس انتخاب شده
        // if ($request->hasFile('picture')) {
        //     $path = $request->file('picture')->store('img','public');
        //     $data['picture'] = $path;
        // }

        $admin = Admin::create($data);
        $admin->password = Hash::make($request->password);
        $admin->save();

        if ($request->picture != null) {
            $filename = time().'.'.$request->picture->extension();
            $request->picture->move(public_path('img'), $filename);


            $admin->update([
                'picture' => "img/".$filename,
            ]);
        }

        if ($request->has('roles')) {
            $admin->roles()->sync($request->roles);
        }

        LogAdminAction::dispatch(
            auth('admin')->id(),
            'افزودن_ادمین',
            'ادمین '.$admin->name.' اضافه شد .',
            request()->ip(),
        );

        return redirect()->route('admin.admins')->with('success','ادمین با موفقیت اضافه شد');
    }

    public function toggleStatus($id)
    {
        $service = Service::findOrFail($id);

        // برعکس کردن وضعیت
        $service->is_active = !$service->is_active;
        $service->save();

        $status = "";
        if ($service->is_active) {
            $status = "فعال";
        }else{
            $status = "غیر فعال";
        }

        LogAdminAction::dispatch(
            auth('admin')->id(),
            'تغییر_وضعیت_خدمت',
            'خدمت به '.$status.' تغییر کرد  .',
            request()->ip(),
        );
        

        return redirect()->back()->with('success', 'وضعیت سرویس تغییر کرد.');
    }

}

