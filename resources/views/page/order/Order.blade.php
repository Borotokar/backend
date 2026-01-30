@extends('layout.admin')
@section('title')
    جزئیات سفارش شماره: {{ $order->id }}
@endsection

@section('body')
<div class="container-fluid py-4">

    {{-- پیام موفقیت/خطا --}}
    @if (Session::get('success'))				
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            {{ Session::get('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">×</button>
        </div>
    @endif

    @if (Session::get('fail'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            {{ Session::get('fail') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">×</button>
        </div>
    @endif


    <h2 class="mb-4 fw-bold">جزئیات سفارش شماره: {{ $order->id }}</h2>

    {{-- اطلاعات سفارش --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-primary text-white fw-bold">
            اطلاعات سفارش
        </div>
        <div class="card-body">
            <p><strong>کاربر:</strong> {{ $order->user->name }}</p>
            <p><strong>شماره تماس کاربر:</strong> {{ $order->user->phone_number }}</p>
            <p><strong>شماره تماس کاربر:</strong> {{ $order->user->sex === "Male" ? 'آقا'  : 'خانم'}}</p>
            <p><strong>خدمت:</strong> {{ $order->service->title }}</p>
            <p><strong>توضیحات:</strong> {{ $order->description }}</p>
            <p><strong>آدرس:</strong> {{ $order->address }}</p>
            <p><strong>شهر:</strong> {{ $order->city }}</p>
            <p>
                <strong>وضعیت:</strong> 
                <span class="badge 
                    @if($order->status == 1) bg-info
                    @elseif($order->status == 2) bg-warning
                    @elseif($order->status == 3) bg-primary
                    @elseif($order->status == 4) bg-success
                    @elseif($order->status == 5) bg-danger
                    @endif">
                    @if($order->status  == 1)
                        درحال پردازش	
                    @elseif($order->status == 2)
                        مشاهده پیشنهاد
                    @elseif($order->status  == 3)
                        درحال انجام
                    @elseif($order->status  == 4)
                        انجام شده
                    @elseif($order->status == 5)
                        لغو شده
                    @endif
                </span>
            </p>
            <p><strong>تاریخ انجام:</strong> {{ \Carbon\Carbon::parse($order->completion_date)->format('Y/m/d') }}</p>
            <p><strong>ساعت انجام:</strong> {{ Str::limit($order->completion_time, 5, "") }} </p>
        </div>
    </div>

    {{-- پاسخ‌های سوالات --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-secondary text-white fw-bold">
            پاسخ‌های سوالات
        </div>
        <div class="card-body">
            @forelse ($order->answers as $answer)
                <div class="mb-2 border-bottom pb-2">
                    <p class="mb-1"><strong>{{ $answer->question->question }}:</strong></p>
                    <p class="text-muted">{{ $answer->answer }}</p>
                </div>
            @empty
                <p class="text-muted">هیچ پاسخی ثبت نشده است.</p>
            @endforelse
        </div>
    </div>

    {{-- پیشنهادها --}}
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white fw-bold">
            پیشنهادها
        </div>
        <div class="card-body">
            @forelse ($order->bids as $bid)
                <div class="border rounded p-3 mb-3 shadow-sm">
                    <p><strong>متخصص:</strong> {{ $bid->expert->first_name }} {{ $bid->expert->last_name }}</p>
                    <p><strong>قیمت پیشنهادی:</strong> {{ number_format($bid->proposed_price) }} تومان</p>
                    <p><strong>نوع پیشنهاد:</strong> 
                        <span class="badge bg-info">
                            {{ $bid->proposalType->name }}
                        </span>
                    </p>
                    <p><strong>پیام متخصص:</strong> {{ $bid->description }}</p>
                    <p><strong>شماره متخصص:</strong> <span dir="ltr">{{ $bid->expert->phone_number }}</span></p>
                </div>
            @empty
                <p class="text-muted">هیچ پیشنهادی ثبت نشده است.</p>
            @endforelse
        </div>
    </div>

</div>
@endsection
