@extends('layout.admin')

@section('body')
<div class="container-fluid ">


@if (Session::get('success'))				
    <div class="alert alert-second alert-shade alert-dismissible " role="alert">
        {{Session::get('success')}}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>
@endif

@if (Session::get('fail'))
                        
    <div class="alert alert-forth alert-shade alert-dismissible " role="alert">
        {{Session::get('fail')}}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>
@endif


<div class="card shade ">
    <div class="card-body">
  
    <h4>مکالمه با: {{ $conversation->user?->name ?? $conversation->expert?->last_name }} </h4>

<div class="border p-3 mb-3" style="height: 400px; overflow-y: auto; background-color: #f9f9f9">
    @foreach($conversation->messages as $msg)
        <div class="mb-2 {{ $msg->sender_type === 'admin' ? 'text-end' : 'text-start' }}">
            <div class="p-2 rounded" style="display: inline-block; background-color: {{ $msg->sender_type === 'admin' ? '#d1e7dd' : '#f8d7da' }}">
                <strong>{{ $msg->sender_type }}</strong><br>
                {{ $msg->message }}
                <div class="text-muted" style="font-size: 10px">{{ jdate($msg->created_at)->format('Y/m/d H:i') }}</div>
            </div>
        </div>
    @endforeach
</div>

<form action="{{ route('admin.support.send') }}" method="POST">
    @csrf
    <input type="hidden" name="conversation_id" value="{{ $conversation->id }}">
    <div class="form-group">
        <textarea name="message" class="form-control" rows="3" placeholder="پیام خود را بنویسید..." required></textarea>
    </div>
    <button type="submit" class="btn btn-success mt-2">ارسال پاسخ</button>
</form>
        
    </div>
</div>
@endsection
    
    
    
 