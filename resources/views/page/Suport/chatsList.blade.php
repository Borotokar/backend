@extends('layout.admin')

@section('body')
<div class="container-fluid">
    <div class="container">
        <h2>مدیریت چت‌ها</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>کاربر ۱</th>
                    <th>کاربر ۲</th>
                    <th>آخرین پیام</th>
                    <th>مشاهده</th>
                </tr>
            </thead>
            <tbody>
                @foreach($conversations as $conversation)
                    <tr>
                        <td>{{ $conversation->user->name }}</td>
                        <td>{{ $conversation->expert->first_name }}</td>
                        <td>{{ optional($conversation->messages->last())->message ?? 'بدون پیام' }}</td>
                        <td>
                            <a href="{{ route('admin.chats.show', $conversation->id) }}" class="btn btn-primary">مشاهده</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
	</table>
	{{ $conversations->links() }}
    </div>
</div>
@endsection

