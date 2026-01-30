@extends('layout.admin')

@section('body')
<div class="container-fluid">
    <div class="container">
        <h2>مشاهده چت</h2>
        <div class="card">
            <div class="card-body">
                <h5>بین {{ $conversation->user->name }} و {{ $conversation->expert->first_name }}</h5>
                <ul class="list-group">
                    @foreach($conversation->messages as $message)
                        <li class="list-group-item {{ $message->sender_id == $conversation->user->id ? 'text-left' : 'text-right' }}">
			    	@if($message->sender_type == "user")
			           <strong>{{ $conversation->user->name }}:</strong> {{ $message->message }}
				@else 
			           <strong>{{ $conversation->expert->first_name }}:</strong> {{ $message->message }}   
				@endif
			</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

