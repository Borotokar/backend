<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Events\MessageSent;
use App\Models\Conversation;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Services\FirebaseNotificationService;
use App\Services\ExpertFirebaseNotificationService;
use Illuminate\Support\Str;
use App\Models\Blocking;


class ConversationController extends Controller
{
    public function index()
    {
        // نمایش تمامی مکالمات کاربر
     	// $conversations = Conversation::with('messages', 'user', 'expert')->where('user_id', auth()->id())->orderByDesc('messages.created_at')->orderBy('seen')->get();
        $conversations = Conversation::with(['messages', 'user', 'expert', 'expert.comments' , 'expert.gallery'])
        ->where('user_id', auth()->id())
        ->withCount(['messages as last_message_time' => function ($query) {
            $query->select(DB::raw('MAX(created_at)'));
        }])
        ->orderByDesc('last_message_time')
        ->orderBy('seen')
        ->get();
	return response()->json($conversations);
    }

    public function show($id)
    {
        
	    $conversation = Conversation::with('messages', 'user', 'expert', 'expert.comments' , 'expert.gallery', 'expert.comments.user')->findOrFail($id);
	    Conversation::where('id', $conversation->id)->update(['seen'=> true]);
	//$conversation->update(['seen', 1]);
	//$conversation->save();
	return response()->json($conversation);
    }
     
    public function deleteChat($id)
    {

            $conversation = Conversation::findOrFail($id);
            Conversation::where('id', $conversation->id)->delete();
        //$conversation->update(['seen', 1]);
        //$conversation->save();
        return response()->json([
		"mes"=>"چت با موفقیت پاک شد"
	]);
    }
    public function sendMessage(Request $request, $id)
    {
        // ارسال پیام در مکالمه
        $conversation = Conversation::findOrFail($id);

        $expertId = $conversation->expert_id;
        $userId = $request->user()->id;

        // بررسی اینکه آیا این متخصص توسط کاربر بلاک شده
        $hasBlocked = Blocking::where('user_id', $userId)
            ->where('expert_id', $expertId)
            ->exists();

        if ($hasBlocked) {
            return response()->json([
                'message' => 'شما اجازه ارسال پیام به این متخصص را ندارید.'
            ], 200);
        }

        try {
            if (!is_null($conversation->expert->fcm_token)) {
                        $fcm = new ExpertFirebaseNotificationService();
                        $fcm->send(
                            $conversation->expert->fcm_token,
                            $conversation->user->name,
                            Str::limit($request->message, 50),

                            ['customData' => '123']
                        );
            }

        } catch (\Throwable $th) {
            //throw $th;
        }
        
        $message = $conversation->messages()->create([
            'message' => $request->message,
            'sender_type' => "user", // user یا specialist
            'sender_id' => $request->user()->id,
	]);

	$conversation->update(['expert_seen'=> false]);


        // ارسال پیام به Pusher
        broadcast(new MessageSent($message))->toOthers();

        return response()->json($message, 201);
    }
    public function seen(){
	    $conversations = Conversation::with('messages', 'user', 'expert')->where('user_id', auth()->id())->where('seen', false)->get();

	    return response()->json(['count' => count($conversations) ], 200);
    }

   public function eindex()
    {
        // ﻦﻣﺎﯿﺷ ﺖﻣﺎﻤﯾ ﻢﮐﺎﻠﻣﺎﺗ ﮎﺍﺮﺑﺭ
        //$conversations = Conversation::with('messages', 'user', 'expert')->where('expert_id', auth()->id())->latest()->orderBy('expert_seen')->get();
	$conversations = Conversation::with(['messages', 'user', 'expert'])
        ->where('expert_id', auth()->id())
        ->withCount(['messages as last_message_time' => function ($query) {
            $query->select(DB::raw('MAX(created_at)'));
        }])
        ->orderByDesc('last_message_time')
        ->orderBy('expert_seen')
        ->get();	    
	return response()->json($conversations);
    }

    public function eshow($id)
    {

            $conversation = Conversation::with('messages', 'user', 'expert')->findOrFail($id);
            Conversation::where('id', $conversation->id)->update(['expert_seen'=> true]);
        //$conversation->update(['seen', 1]);
        //$conversation->save();
        return response()->json($conversation);
    }

    public function esendMessage(Request $request, $id)
    {
        // ﺍﺮﺳﺎﻟ ﭗﯾﺎﻣ ﺩﺭ ﻢﮐﺎﻠﻤﻫ
        $conversation = Conversation::findOrFail($id);

        $expertId = $request->user()->id;
        $userId = $conversation->user_id;

        // بررسی اینکه آیا این متخصص توسط کاربر بلاک شده
        $hasBlocked = Blocking::where('user_id', $userId)
            ->where('expert_id', $expertId)
            ->exists();

        if ($hasBlocked) {
            return response()->json([
                'message' => 'شما اجازه ارسال پیام به این کاربر را ندارید.'
            ], 200);
        }
        
        $message = $conversation->messages()->create([
            'message' => $request->message,
            'sender_type' => "expert", // user ﯼﺍ specialist
            'sender_id' => $request->user()->id,
        ]);
        
        $conversation->update(['seen'=> false]);

        // ﺍﺮﺳﺎﻟ ﭗﯾﺎﻣ ﺐﻫ Pusher
        //broadcast(new MessageSent($message))->toOthers();
        $fcm = new FirebaseNotificationService();

        try {
            if (!is_null($conversation->user->fcm_token)) {
                $fcm->send(
                    $conversation->user->fcm_token,
                    $conversation->expert->first_name .' '.$conversation->expert->last_name,
                //   "..",
                    Str::limit($request->message, 50),
                    ['customData' => '123']
                );
            }
        } catch (\Throwable $th) {
            //throw $th;
        }

        return response()->json($message, 200);
    }
    public function eseen(){
            $conversations = Conversation::with('messages', 'user', 'expert')->where('expert_id', auth()->id())->where('expert_seen', false)->get();

            return response()->json(['count' => count($conversations) ], 200);
    } 
}
