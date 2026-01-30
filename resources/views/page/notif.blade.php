    <h1>نوتیفیکیشن‌ها</h1>
    <ul>
        @foreach ($notifications as $notification)
            <li>
                {{ $notification->message }}
                <span>{{ $notification->created_at }}</span>
                @if (!$notification->read)
                    <form action="{{ route('admin.notifications.markAsRead', $notification->id) }}" method="post">
                        @csrf
                        <button type="submit">علامت خوانده شده</button>
                    </form>
                @endif
            </li>
        @endforeach
    </ul>



