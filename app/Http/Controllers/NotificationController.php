<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::orderBy('created_at', 'desc')->get();
        return view('page.notif', compact('notifications'));
    }

    public function markAsRead(Notification $notification)
    {
        $notification->update(['read' => true]);
        return back();
    } 
}
