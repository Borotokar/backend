@extends('layout.admin')
@section('title')
    لیست متخصصین بروتوکار
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

    <div class="container mt-5">

    {{-- نمایش اطلاعات متخصص --}}
    <h2 class="mb-4">اطلاعات متخصص</h2>

    {{-- کارت اطلاعات پایه --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-primary text-white">اطلاعات پایه</div>
        <div class="card-body">
                            <div class="col-md-3 mb-3">
                    <img src="{{ URL::asset($expert->profile_image) }}" alt="Profile" class="img-fluid rounded" style="max-height:150px;">
                </div>
            <p><strong>شماره تلفن:</strong> {{ $expert->phone_number }}</p>
            <p><strong>نام:</strong> {{ $expert->first_name }}</p>
            <p><strong>نام خانوادگی:</strong> {{ $expert->last_name }}</p>
            <p><strong>کد ملی:</strong> {{ $expert->national_id }}</p>
            <p><strong>تاریخ تولد:</strong>  {{ \Carbon\Carbon::parse($expert->birth_date)->format('Y/m/d') }} </p>
            <p><strong>گارانتی:</strong> {{ $expert->guarantee }}</p>
            <p><strong>درباره من:</strong> {{ $expert->about_me }}</p>
        </div>
    </div>

    {{-- کارت اطلاعات نوع متخصص --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-info text-white">اطلاعات نوع متخصص</div>
        <div class="card-body">
            <p><strong>نوع:</strong> {{ $expert->type == "business_unit" ? "واحد صنفی" : ($expert->type == "self_employed" ? "خویش فرما" : "شرکت") }}</p>
            @if($expert->type == 'company')
                <p><strong>نام شرکت:</strong> {{ $expert->company_name }}</p>
                <p><strong>شماره ثبت شرکت:</strong> {{ $expert->registration_number }}</p>
            @endif
            <p><strong>آدرس:</strong> {{ $expert->address }}</p>
            <p><strong>استان:</strong> {{ $expert->province }}</p>
            <p><strong>شهر:</strong> {{ $expert->city }}</p>
            <p><strong>تخصص‌ها:</strong> 
                @foreach($expert->services as $item)
                    {{ $item->title }}{{ !$loop->last ? ',' : '' }}
                @endforeach
            </p>
            <p><strong>وضعیت:</strong> {{ $expert->is_active ? 'فعال' : 'غیرفعال' }}</p>
        </div>
    </div>

    {{-- کارت شبکه‌های اجتماعی --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-warning text-dark">شبکه‌های اجتماعی</div>
        <div class="card-body">
            <p><strong>تلگرام:</strong> <a href="https://t.me/{{ $expert->telegram_link }}">{{ $expert->telegram_link }}</a></p>
            <p><strong>واتساپ:</strong> <a href="{{ $expert->whatsapp_link }}">{{ $expert->whatsapp_link }}</a></p>
            <p><strong>ایتا:</strong> <a href="{{ $expert->eitaa_link }}">{{ $expert->eitaa_link }}</a></p>
            <p><strong>وبسایت:</strong> <a href="https://{{ $expert->website_link }}">{{ $expert->website_link }}</a></p>
            @if($expert->blue_tick)
                <p><strong>تیک آبی:</strong> ✔️</p>
            @endif
        </div>
    </div>

    {{-- کارت گالری --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-success text-white">گالری تصاویر</div>
        <div class="card-body">
            <div class="row">

                @foreach($expert->gallery as $image)
                    <div class="col-md-3 mb-3">
                        <img src="{{ URL::asset($image->path) }}" alt="Gallery Image" class="img-fluid rounded" style="max-height:100px;">
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- کارت مستندات --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-secondary text-white">مستندات</div>
        <div class="card-body">
            @if($expert->documents)
                @foreach($expert->documents as $document)
                    <img src="{{ URL::asset($document->path) }}" alt="Document" class="img-fluid mb-2" style="max-height:300px;">
                @endforeach
            @endif
        </div>
    </div>

    {{-- کارت ویدیو --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-dark text-white">ویدیو احراز هویت</div>
        <div class="card-body">
            @if($expert->video)
                <video width="200" height="300" controls>
                    <source src="{{ URL::asset($expert->video->video_path) }}" type="video/mp4">
                    مرورگر شما ویدیو را پشتیبانی نمی‌کند.
                </video>
            @else
                <p>ویدیو موجود نیست.</p>
            @endif
        </div>
    </div>

</div>
<div class="container ">
                <h4> تایید متخصص</h4>
                <form action="{{ route('admin.expertaccesshandller', ['id'=>$expert->id]) }}" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        
                        <div class="col-5">
                            <div class="form-group bmd-form-group is-filled">
                                <label for="exampleFormControlSelect1" class="bmd-label-static">دسته بندی</label>
                                        <select class="form-control" id="exampleFormControlSelect1" name="status" onchange="toggleReasonDropdown()">    
				<br>
                                    <option value="notactive"><p>
                                        رد کردن اطلاعات هویتی 
                                    </p></option>
                                    <option value="active"><p>
                                        تایید هویت     
                                    </p></option>
    
                                </select>
                            </div>    
                        </div> 
		    </div>

	                    <div class="col-5 hidden" id="reasonDiv">
                        <div class="form-group bmd-form-group is-filled">
                            <label for="reasonSelect" class="bmd-label-static">ﺩﻼﯿﻟ ﺭﺩ ﮎﺍﺮﺑﺭ</label>
                            <select class="form-control" id="reasonSelect" name="reason">
                                <option value="1">نقص مدارک هویتی</option>
                                <option value="2">نقص مدارک مربوط به تخصص</option>
                                <option value="3">نقص در ویدیو احراز هویت</option>
                                <option value="4">ثبت تخصص غیر مرتبط</option>
                                <option value="5">ثبت ناقص آدرس</option>
                            </select>
			</div>
	            <label for="des" class="bmd-label-static">دلایل رد متخصص : </label>
    	            <span class="bmd-form-group is-filled"><textarea class="form-control" id="des" rows="3" name="des"></textarea></span>
 
                    </div>		
        @error('reason')
            <div class="d-block text-danger">
                {{$message}}
            </div>
            @enderror
        </div>
        <input type="submit" class="btn btn-primary btn-sm btn-block" value="انجام عملیات">
                </div>

        </form>
</div>
</div>
</div>

                    

                




@endsection
@section('script')
<script>
        function toggleReasonDropdown() {
            var statusSelect = document.getElementById("exampleFormControlSelect1");
            var reasonDiv = document.getElementById("reasonDiv");

            if (statusSelect.value === "notactive") {
                reasonDiv.style.display = "block";
            } else {
                reasonDiv.style.display = "none";
            }
        }
    </script>
@endsection
