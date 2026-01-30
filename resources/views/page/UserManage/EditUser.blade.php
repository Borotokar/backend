@extends('layout.admin')
@section('title')
    لیست کاربران بروتوکار
@endsection
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
                
                <div class="row">
                    <h5 class="card-title">ویرایش کاربر</h5>
                    <div style="width: 20px"></div>
                    {{-- <a href="#addService"><button type="button" class="btn flat f-success btn-block fnt-xxs text-center " >افزودن</button></a> --}}
                </div>
                <hr>

                        
                <form action="{{ route('admin.userupdate', $user->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">نام:</label>
                        <input type="text" id="name" name="name" class="form-control" value="{{ $user->name }}">
                    </div>

                    @error('name')
                        <div class="d-block text-danger">
                            {{$message}}
                        </div>
                    @enderror
                    
                    <div class="mb-3">
                        <label for="username" class="form-label">نام کاربری:</label>
                        <input type="text" id="username" name="username" class="form-control" value="{{ $user->username }}">
                    </div>

                    @error('username')
                        <div class="d-block text-danger">
                            {{$message}}
                        </div>
                    @enderror
                    
                    <div class="mb-3">
                        <label for="phone_number" class="form-label">شماره تلفن:</label>
                        <input type="text" id="phone_number" name="phone_number" class="form-control" value="{{ $user->phone_number }}">
                    </div>

                    @error('phone_number')
                        <div class="d-block text-danger">
                            {{$message}}
                        </div>
                    @enderror
                    
                    <div class="mb-3">
                        <label for="picture" class="form-label">عکس:</label>
                        <div class="custom-file" dir="ltr">
                            <label class="custom-file-label" for="inputGroupFile01" style="text-align: left">انتخاب کنید</label>
                            <input  type="file" class="custom-file-input" id="inputGroupFile01" name="image" aria-describedby="inputGroupFileAddon01">
                          </div>
                    </div>

                    @error('image')
                        <div class="d-block text-danger">
                            {{$message}}
                        </div>
                    @enderror

                    
                    <div class="mb-3">
                        <label for="status" class="form-label">وضعیت:</label>
                        <select id="status" name="status" class="form-select" required>
                            <option value="pending" {{ $user->status == 'pending' ? 'selected' : '' }}>در انتظار</option>
                            <option value="active" {{ $user->status == 'active' ? 'selected' : '' }}>فعال</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="sex" class="form-label">جنسیت:</label>
                        <select id="sex" name="sex" class="form-select" required>
                            <option value="Male" {{ $user->sex == 'Male' ? 'selected' : '' }}>مرد</option>
                            <option value="Fmale" {{ $user->sex == 'Fmale' ? 'selected' : '' }}>زن</option>
                        </select>
                    </div>
            
                    <button type="submit" class="btn btn-success">ذخیره</button>
                </form>
            
		<div class="modal-body">
                <form method="POST" action="{{ route('admin.activedeactiveuser', ['id' => $user->id]) }}">
                    @csrf
                    <label for="reason">دلیل غیرفعال شدن:</label>
                    <select name="reason" class="form-control">
                        <option value="نقص قوانین">نقص قوانین</option>
                        <option value="انتشار محتوای نامناسب">انتشار محتوای نامناسب</option>
                        <option value="سفارش و لغو مکرر">سفارش و لغو مکرر</option>
                        <option value="ثبت نظر غیرمربوط">ثبت نظر غیرمربوط</option>
                        <option value="دیگر">دیگر (توضیح دهید)</option>
                    </select>
                    <textarea name="custom_reason" class="form-control mt-2" placeholder="اگر دلیل خاصی دارید، اینجا بنویسید"></textarea>
                    <button type="submit" class="btn btn-danger mt-3">تأیید غیرفعال‌سازی</button>
                </form> <!-- فرم در اینجا بسته شد -->
            </div> 
	</div>
        </div>
</div>


</div>
</div>



@endsection
