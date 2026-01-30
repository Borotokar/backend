@extends('layout.admin')

@section('body')
<div class="container mt-4">
    <!-- <div class="row justify-content-center"> -->
        <div class="col-md">

            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">افزودن ادمین جدید</h4>
                </div>
                <div class="card-body p-4">

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">نام</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">نام کاربری</label>
                            <input type="text" name="username" class="form-control" value="{{ old('username') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">شماره تماس</label>
                            <input type="text" name="phone_number" class="form-control" value="{{ old('phone_number') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">رمز عبور</label>
                            <input type="password" name="password" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">تصویر پروفایل</label>
                            <input type="file" name="picture" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">نقش‌ها</label>
                            <select name="roles[]" class="form-select" multiple>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">برای انتخاب چند نقش کلید Ctrl یا Cmd را نگه دارید</small>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.admins') }}" class="btn btn-secondary px-4">بازگشت</a>
                            <button type="submit" class="btn btn-success px-4">ثبت</button>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection
