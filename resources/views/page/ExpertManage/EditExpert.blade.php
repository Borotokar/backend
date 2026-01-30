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
            <p><strong>تاریخ تولد:</strong> {{ \Carbon\Carbon::parse($expert->birth_date)->format('Y/m/d') }} </p>
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


                

<div class="container mt-5">

    {{-- فرم ویرایش --}}
    <form action="{{ route('admin.upadetexpert', ['id'=>$expert->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- کارت اطلاعات پایه --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-primary text-white">
                اطلاعات پایه
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">شماره تلفن</label>
                        <input type="text" name="phone_number" class="form-control" value="{{ $expert->phone_number }}" required>
                        @error('phone_number')<div class="text-danger">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">نام</label>
                        <input type="text" name="first_name" class="form-control" value="{{ $expert->first_name }}" required>
                        @error('first_name')<div class="text-danger">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">نام خانوادگی</label>
                        <input type="text" name="last_name" class="form-control" value="{{ $expert->last_name }}" required>
                        @error('last_name')<div class="text-danger">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">کد ملی</label>
                        <input type="text" name="national_id" class="form-control" value="{{ $expert->national_id }}" required>
                        @error('national_id')<div class="text-danger">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">تاریخ تولد</label>
                        <input type="text" name="birth_date" class="form-control" value="{{ $expert->birth_date }}" required>
                        @error('birth_date')<div class="text-danger">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">گارانتی</label>
                        <input type="text" name="guarantee" class="form-control" value="{{ $expert->guarantee }}" required>
                        @error('guarantee')<div class="text-danger">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-10"> 
                        <label for="exampleFormControlTextarea1" class="bmd-label-static">درباره ی من :</label>
                         <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="about_me" >{{$expert->about_me}}
                         </textarea> 
                        @error('about_me')<div class="text-danger">{{ $message }}</div>@enderror
                    
                </div>

                        <div class="mb-3">
            <label for="is_active" class="form-label">وضعیت:</label>
            <select id="is_active" name="is_active" class="form-control" required>
                <option value="1" {{ $expert->is_active ? 'selected' : '' }}>فعال</option>
                <option value="0" {{ !$expert->is_active ? 'selected' : '' }}>غیرفعال</option>
            </select>
        </div>
        @error('is_active')
            <div class="d-block text-danger">
                {{$message}}
            </div>
        @enderror
            </div>
        </div>

        {{-- کارت اطلاعات نوع متخصص --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-info text-white">
                اطلاعات نوع متخصص
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">نوع</label>
                    <select name="type" class="form-control" required>
                        <option value="business_unit" {{ $expert->type == 'business_unit' ? 'selected' : '' }}>واحد صنفی</option>
                        <option value="self_employed" {{ $expert->type == 'self_employed' ? 'selected' : '' }}>خویش فرما</option>
                        <option value="company" {{ $expert->type == 'company' ? 'selected' : '' }}>شرکت</option>
                    </select>
                    @error('type')<div class="text-danger">{{ $message }}</div>@enderror
                </div>

                @if($expert->type == 'business_unit' || $expert->type == 'self_employed')
                    <div class="mb-3">
                        <label class="form-label">تصویر شناسنامه یا کارت ملی</label>
                        <input type="file" name="documents[national_id_photo]" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">عکس پرسنلی</label>
                        <input type="file" name="documents[personal_photo]" class="form-control">
                    </div>
                    @if($expert->type == 'business_unit')
                        <div class="mb-3">
                            <label class="form-label">عکس جواز کسب</label>
                            <input type="file" name="documents[business_license_photo]" class="form-control">
                        </div>
                    @endif
                @elseif($expert->type == 'company')
                    <div class="mb-3">
                        <label class="form-label">نام شرکت</label>
                        <input type="text" name="company_name" class="form-control" value="{{ $expert->company_name }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">شماره ثبت</label>
                        <input type="text" name="registration_number" class="form-control" value="{{ $expert->registration_number }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">تصویر آخرین آگهی تاسیس</label>
                        <input type="file" name="documents[establishment_photo]" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">تصویر آگهی آخرین تغییرات</label>
                        <input type="file" name="documents[changes_photo]" class="form-control">
                    </div>
                @endif
            </div>
        </div>

        {{-- کارت شبکه‌های اجتماعی --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-warning text-dark">
                شبکه‌های اجتماعی
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">تلگرام</label>
                    <input type="text" name="telegram_link" class="form-control" value="{{ $expert->telegram_link }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">واتساپ</label>
                    <input type="text" name="whatsapp_link" class="form-control" value="{{ $expert->whatsapp_link }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">ایتا</label>
                    <input type="text" name="eitaa_link" class="form-control" value="{{ $expert->eitaa_link }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">وبسایت</label>
                    <input type="text" name="website_link" class="form-control" value="{{ $expert->website_link }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">تیک آبی</label>
                    <select name="blue_tick" class="form-control">
                        <option value="1" {{ $expert->blue_tick ? 'selected' : '' }}>فعال</option>
                        <option value="0" {{ !$expert->blue_tick ? 'selected' : '' }}>غیرفعال</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-warning text-dark">
               آدرس
            </div>
            <div class="card-body">
            <div class="mb-3">
            <label for="address" class="form-label">آدرس:</label>
            <input type="text" id="address" name="address" class="form-control" value="{{ $expert->address }}" required>
        </div>
        @error('address')
            <div class="d-block text-danger">
                {{$message}}
            </div>
        @enderror
        <div class="mb-3">
            <label for="province" class="form-label">استان:</label>
            <input type="text" id="province" name="province" class="form-control" value="{{ $expert->province }}" required>
        </div>
        @error('province')
            <div class="d-block text-danger">
                {{$message}}
            </div>
        @enderror
        <div class="mb-3">
            <label for="city" class="form-label">شهر:</label>
            <input type="text" id="city" name="city" class="form-control" value="{{ $expert->city }}" required>
        </div>
        @error('city')
            <div class="d-block text-danger">
                {{$message}}
            </div>
        @enderror
            </div>
        </div>

        {{-- کارت گالری و خدمات --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-success text-white">
                گالری و خدمات
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">گالری تصاویر</label>
                    <input type="file" name="gallery[]" class="form-control" multiple>
                </div>
                <div class="mb-3">
                    <label class="form-label">خدمات</label>
                    <select name="services[]" class="form-control" multiple>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}" {{ in_array($service->id, $expert->services->pluck('id')->toArray()) ? 'selected' : '' }}>{{ $service->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">تصویر پروفایل</label>
                    <input type="file" name="profile_image" class="form-control">
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary ">ویرایش اطلاعات</button>
    </form>
</div>


@endsection

@section('script')
<script>
       document.addEventListener('DOMContentLoaded', function () {
        const typeField = document.getElementById('type');
        const additionalFields = document.getElementById('additional_fields');

        function updateAdditionalFields() {
            const type = typeField.value;
            let fields = '';

            if (type === 'business_unit' || type === 'self_employed') {
                fields += `
                    <div class="mb-3">
                        <label for="national_id_photo" class="form-label">تصویر شناسنامه یا کارت ملی:</label>
                        <input type="file" id="national_id_photo" name="documents[national_id_photo]" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="personal_photo" class="form-label">عکس پرسنلی:</label>
                        <input type="file" id="personal_photo" name="documents[personal_photo]" class="form-control">
                    </div>`;
                if (type === 'business_unit') {
                    fields += `
                    <div class="mb-3">
                        <label for="business_license_photo" class="form-label">عکس جواز کسب:</label>
                        <input type="file" id="business_license_photo" name="documents[business_license_photo]" class="form-control">
                    </div>`;
                }
            } else if (type === 'company') {
                fields += `
                    <div class="mb-3">
                        <label for="company_name" class="form-label">نام شرکت:</label>
                        <input type="text" id="company_name" name="company_name" class="form-control" value="{{ $expert->company_name }}">
                    </div>
                    <div class="mb-3">
                        <label for="registration_number" class="form-label">شماره ثبت:</label>
                        <input type="text" id="registration_number" name="registration_number" class="form-control" value="{{ $expert->registration_number }}">
                    </div>
                    <div class="mb-3">
                        <label for="establishment_photo" class="form-label">تصویر آخرین آگهی تاسیس:</label>
                        <input type="file" id="establishment_photo" name="documents[establishment_photo]" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="changes_photo" class="form-label">تصویر آگهی آخرین تغییرات:</label>
                        <input type="file" id="changes_photo" name="documents[changes_photo]" class="form-control">
                    </div>`;
            }

            additionalFields.innerHTML = fields;
        }

        typeField.addEventListener('change', updateAdditionalFields);
        updateAdditionalFields();
    });
    jalaliDatepicker.startWatch();
    function showDate(){
        jalaliDatepicker.show(document.getElementById("birth_date"), { placeholder: "yyyy-MM-dd" })
    }
</script>
<script>
    
</script>
@endsection
