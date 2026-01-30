<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\expert;

class ReportController extends Controller
{
   public function store(Request $request)
    {
        $request->validate([
            'expert_id' => 'required|exists:experts,id',
            'type' => 'required|in:chat,profile',
            'description' => 'required|string|max:10000',
        ]);

        Report::create([
            'user_id' => $request->user()->id,
            'expert_id' => $request->expert_id,
            'type' => $request->type,
            'description' => $request->description,
            'status' => 'pending',
        ]);

	return response()->json(["mesage"=>"گزارش با موفقیت ثبت شد"]);
        //return redirect()->back()->with('success', 'گزارش شما ثبت شد و در حال بررسی است.');
    }

    // نمایش گزارش‌ها برای ادمین
    public function index()
    {
        $reports = Report::latest()->paginate(10);
        return view('page.Suport.Reports', compact('reports'));
    }

    // تغییر وضعیت گزارش توسط ادمین
    public function updateStatus($id, $status)
    {
        $report = Report::findOrFail($id);
        $report->update(['status' => $status]);

        return redirect()->back()->with('success', 'وضعیت گزارش به‌روزرسانی شد.');
    } 
}
