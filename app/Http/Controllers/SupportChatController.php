<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupportMessages;
use App\Models\SupportConversations;
use Illuminate\Support\Facades\DB;
use App\Services\FirebaseNotificationService;
use Illuminate\Support\Str;


class SupportChatController extends Controller
{
    public function uindex()
    {
    $conversations = SupportConversations::with('latestMessage')
        ->whereNotNull('user_id')
        ->withCount([
            'messages as last_message_time' => function ($query) {
                $query->select(DB::raw('MAX(created_at)'));
            },
            // 👇 این قسمت تعداد پیام‌های نخونده رو میاره
            'messages as unread_count' => function ($query) {
                $query->where('is_read', false);
            }
        ])
        // اولویت با مکالمه‌هایی که unread_count > 0 هستن
        ->orderByDesc('unread_count')
        ->orderByDesc('last_message_time')
        ->paginate(15);

        return view('page.Suport.index', compact('conversations'));
    }

    public function eindex()
    {
        // $conversations = SupportConversations::whereNotNull('expert_id')
        // ->withCount(['messages as last_message_time' => function ($query) {
        //     $query->select(DB::raw('MAX(created_at)'));
        // }])
        // ->orderByDesc('last_message_time')
        // ->paginate(15);
            $conversations = SupportConversations::with('latestMessage')
        ->whereNotNull('expert_id')
        ->withCount([
            'messages as last_message_time' => function ($query) {
                $query->select(DB::raw('MAX(created_at)'));
            },
            // 👇 این قسمت تعداد پیام‌های نخونده رو میاره
            'messages as unread_count' => function ($query) {
                $query->where('is_read', false);
            }
        ])
        // اولویت با مکالمه‌هایی که unread_count > 0 هستن
        ->orderByDesc('unread_count')
        ->orderByDesc('last_message_time')
        ->paginate(15);
        return view('page.Suport.eindex', compact('conversations'));
        // return $conversations;
    }

    public function show($id)
    {
        $conversation = SupportConversations::with('messages')->findOrFail($id);
        
        $conversation->messages()
        ->where('is_read', false)
        ->update(['is_read' => true]);

        return view('page.Suport.show', compact('conversation'));
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|exists:support_conversations,id',
            'message' => 'required|string'
        ]);

        $conversation = SupportConversations::findOrFail($request->conversation_id);

        SupportMessages::create([
            'conversation_id' => $request->conversation_id,
            'sender_type' => 'admin',
            'sender_id' => auth()->id(),
            'message' => $request->message
        ]);

        try {
            $fcm = new FirebaseNotificationService();

            if (!is_null($conversation->user->fcm_token)) {
                $fcm->send(
                    $conversation->user->fcm_token,
                    "پشتیبانی بروتوکار",
                //   "..",
                    Str::limit($request->message, 50),
                    ['customData' => '123']
                );
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
        return back()->with('success', 'پیام ارسال شد.');
    }
}
