<?php

namespace App\Http\Controllers;

use App\Models\ProposalType;
use Illuminate\Http\Request;

class ProposalTypeController extends Controller
{
    public function index()
    {
        $proposalTypes = ProposalType::all();
        return view('page.Service.PTSList', compact('proposalTypes'));
    }

    // نمایش فرم ساخت نوع پیشنهاد جدید
    //public function create()
    //{
    //    return view('admin.proposal_types.create');
    //}

    // ذخیره نوع پیشنهاد جدید
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        ProposalType::create([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.proposal_types.index')->with('success', 'نوع پیشنهاد با موفقیت اضافه شد');
    }

    // نمایش فرم ویرایش نوع پیشنهاد
    public function edit($id)
    {
        $type = ProposalType::findOrFail($id);
        return view('page.Service.PTSEdit', compact('type'));
    }

    // به‌روزرسانی نوع پیشنهاد
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $proposalType = ProposalType::findOrFail($id);
        $proposalType->update([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.proposal_types.index')->with('success', 'نوع پیشنهاد با موفقیت ویرایش شد');
    }

    // حذف نوع پیشنهاد
    public function destroy($id)
    {
        $proposalType = ProposalType::findOrFail($id);
        $proposalType->delete();

        return redirect()->route('admin.proposal_types.index')->with('success', 'نوع پیشنهاد با موفقیت حذف شد');
    }
}
