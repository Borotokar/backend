@extends('layout.admin')

@section('title')
    ویرایش ادمین بروتوکار
@endsection

@section('body')
<div class="container-fluid ">

    {{-- پیام موفقیت یا خطا --}}
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

    {{-- کارت پروفایل ادمین --}}
    <div class="card shade mb-4">
        <div class="card-body d-flex align-items-center">
            <img src="{{ URL::asset($admin->picture ?? '/images/default.png') }}" 
                 class="rounded-circle me-3" alt="profile" width="80" height="80">
            <div>
                <h5 class="fw-bold mb-1">{{ $admin->name }}</h5>
                <p class="mb-0">📱 {{ $admin->phone_number }}</p>
                <p class="mb-0">👤 {{ $admin->username }}</p>
            </div>
        </div>
    </div>

    {{-- گزارشات ادمین (اسکرول افقی) --}}
    <div class="card shade mb-4">
        <div class="card-body">
            <h6 class="fw-bold mb-3">آخرین فعالیت‌ها</h6>
            <div class="d-flex overflow-auto" style="gap: 1rem;">
                @forelse($admin->logs as $log)
                    <div class="card p-3" style="min-width: 220px;">
                        <p class="mb-1 fw-bold">{{ $log->action }}</p>
                        <p class="small text-muted mb-1">{{ $log->description }}</p>
                        <span class="text-secondary small">{{ $log->created_at->diffForHumans() }}</span>
                    </div>
                @empty
                    <div class="text-muted">هیچ گزارشی ثبت نشده</div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="card mb-4">
    <div class="card-header">
        آخرین ورود
    </div>
    <div class="card-body p-0">
        <ul class="list-group list-group-flush">
            @foreach($admin->sessions as $session)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span>
                        <strong>ورود:</strong> {{ Morilog\Jalali\Jalalian::fromCarbon($session->login_at)->format('Y/m/d H:i');  }}  
                    </span>
                </li>
            @endforeach
        </ul>
    </div>
</div>


    {{-- فرم ویرایش --}}
    <div class="card shade">
        <div class="jumbotron shade pt-5 addService" id="addService">
            <h4 class="c-grey pt-3 pb-3">ویرایش ادمین</h4>
            <hr class="mt-0 mb-4">
            <form class="p-2" action="{{ route('admin.editAdmin_handller', ['id'=>$admin->id]) }}" 
                  method="POST" enctype="multipart/form-data" id="serviceForm">
                @csrf

                <div class="form-row">
                    <div class="col-4">
                        <label class="bmd-label-static">نام</label>
                        <input type="text" class="form-control" name="name" value="{{ $admin->name }}">
                        @error('name')
                            <div class="d-block text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-4">
                        <label class="bmd-label-static">شماره تلفن</label>
                        <input type="text" class="form-control" name="phone_number" value="{{ $admin->phone_number }}">
                        @error('phone_number')
                            <div class="d-block text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-4">
                        <label class="bmd-label-static">نام کاربری</label>
                        <input type="text" class="form-control" name="username" value="{{ $admin->username }}">
                        @error('username')
                            <div class="d-block text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-5">
                        <label class="bmd-label-static">تصویر</label>
                        <div class="custom-file" dir="ltr">
                            <label class="custom-file-label" for="inputGroupFile01" style="text-align: left">انتخاب کنید</label>
                            <input type="file" class="custom-file-input" id="inputGroupFile01" name="image">
                        </div>
                        @error('image')
                            <div class="d-block text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 mt-3">
                        <label for="roles">نقش‌ها:</label>
                        <select name="roles[]" id="roles" class="form-control" multiple>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ $admin->roles->contains($role->id) ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <br>
                <input type="submit" class="btn btn-primary btn-sm btn-block" value="ویرایش">
            </form>
        </div>
    </div>

</div>
@endsection
