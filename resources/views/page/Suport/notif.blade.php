@extends('layout.admin')
@section('title')
ارسال اعلان به کاربر | بروتوکار
@endsection
@section('body')
<div class="container-fluid">
    <h1>ارسال اعلان به کاربران</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Form for creating a new notification -->
<div class="card shade ">
        <div class="card-body">
            <form action="{{ route('admin.notif') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="title">عنوان</label>
                    <input type="text" name="title" id="title" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="message">پیام</label>
                    <textarea name="message" id="message" class="form-control" rows="3" required></textarea>
                </div>
                <div class="form-group">
                    <label for="user_id">دریافت کننده (دریافت کننده را انتخاب کنید)</label>
                    <select name="user_id[]" id="user_id" class="form-control" multiple>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                    <small class="form-text text-muted">برای ارسال به همه‌ی کاربران هیچ کدام را انتخاب نکنید</small>
                </div>
                <button type="submit" class="btn btn-primary">ارسال نوتیفیکیشن</button>
            </form>
        
    </div>

    <!-- Table for showing existing notifications -->

    <table class="table table-bordered">
    <thead>
        <tr>
            <th>ردیف</th>
            <th>عنوان</th>
            <th>متن پیام</th>
            <th>تعداد ارسال</th>
            <th>عملیات</th>
        </tr>
    </thead>
    <tbody>
        @foreach($notifications as $notification)
            <tr>
                <th scope="row">{{ $loop->iteration }}</th>
                <td>{{ $notification->title }}</td>
                <td>{{ $notification->message }}</td>
                <td>{{ $notification->total }} نفر</td>
                <td>
                    <form action="{{ route('admin.deletenotif', $notification->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">حذف</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

</div>
</div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        $('#user_id').select2({
            placeholder: "جستجو و انتخاب کنید...",
            allowClear: true
        });
    });
</script>
@endsection