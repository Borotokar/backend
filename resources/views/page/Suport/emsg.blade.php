@extends('layout.admin')
@section('title')
ارسال  پیام به متخصص | بروتوکار
@endsection
@section('body')
<div class="container-fluid">
    <h1>ارسال پیام به متخصص</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Form for creating a new notification -->
<div class="card shade ">
        <div class="card-body">
            <form action="{{ route('admin.emsgh') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="message">پیام :</label>
                    <textarea name="message" id="message" class="form-control" rows="3" required></textarea>
                </div>
                <div class="row-group">
                    <input type="checkbox" name="name" value="0">
                    <label for="name">نمایش نام متخصص</label>
                </div>
                    <div class="form-group">
                        <label for="user_id">دریافت کننده (دریافت کننده را انتخاب کنید)</label>
                        <select name="user_id[]" id="user_id" class="form-control" multiple>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">برای ارسال به همه‌ی کاربران هیچ کدام را انتخاب نکنید</small>
                    </div>

                <button type="submit" class="btn btn-primary">ارسال پیام</button>
            </form>

    </div>
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
