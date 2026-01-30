<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserReport;

class UserReportController extends Controller
{
   public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'violation_type' => 'required|in:chat,profile,order',
            'description' => 'nullable|string|max:1000',
        ]);

        UserReport::create([
            'expert_id' =>  $request->user()->id, // متخصصی که گزارش می‌دهد
            'user_id' => $request->user_id,
            'violation_type' => $request->violation_type,
            'description' => $request->description,
        ]);

        return response()->json(['message' => 'گزارش تخلف ارسال شد'], 201);
    }

    // دریافت لیست گزارش‌های ارسال‌شده
    public function index()
    {
        $reports = UserReport::with(['user', 'expert'])->latest()->paginate(10);
        return view('page.Suport.userReports', compact('reports'));
    }

    // بررسی وضعیت گزارش
    public function updateStatus(Request $request, $id)
    {
        $report = UserReport::findOrFail($id);
        $request->validate(['status' => 'required|in:pending,reviewed,rejected']);
        $report->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'وضعیت گزارش به‌روزرسانی شد.');

    } 
}
