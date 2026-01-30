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

        <div class="card shade ">
    <div class="card-body">
        <form action="{{ route('admin.expert.search') }}" method="GET">
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="جستوجو متخصصین" value="{{ request('search') }}" aria-describedby="button-addon2">
                        
                    </div>
                </div>
                <div class="col-md-2">
                    <select name="badge" class="form-control" onchange="this.form.submit()">
                        <option value="">همه</option>
                        <option value="blue" {{ request('badge') == 'blue' ? 'selected' : '' }}>تیک آبی</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <button class="btn btn-primary" type="submit">جستوجو</button>
                </div>
            </div>
        </form> 
        <div class="row">
            <h5 class="card-title">لیست متخصصین برو‌توکار</h5>
            <div style="width: 20px"></div>
            <a href="#add"><button type="button" class="btn flat f-success btn-block fnt-xxs text-center">افزودن</button></a>
        </div>

                <hr>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">ردیف</th>
                            <th scope="col">تصویر</th>
                            <th scope="col">نام و نام خانوادگی</th>
                            <th scope="col">شماره تلفن</th>
                            <th scope="col">کدملی</th>
                            <th scope="col">تاریخ تولد</th>
                            <th scope="col">نوع</th>
                            <th scope="col">آدرس</th>
                            <th scope="col">شهر</th>
                            <th scope="col">وضعیت</th>
                            <th scope="col">عملیات</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($experts as $item)
                            <tr>
                                <th scope="row">{{$loop->iteration }}</th>
                                <td> <img src="{{URL::asset($item['profile_image'])}}" alt="profile Pic" height="50" width="50"></td>
                                <td>{{$item->first_name.' '.$item->last_name}}</td>
                                <td>{{$item->phone_number}}</td>
                                <td>{{$item->national_id}}</td>
                                <td>{{$item->birth_date}}</td>
                                <td>{{$item->type == "business_unit" ? "واحد صنفی" : ($item->type == "self_employed" ? "خویش فرما" : "شرکت") }}</td>
                                <td><p>{{Str::limit($item->address, $limit = 50, $end = '...')}}</p></td>
                                <td>{{$item->city}}</td>
                                <td>{{$item->is_active == false ? "غیرفعال" : "فعال"}}</td>
                                <td>
                                    <div class="c-grey text-center  ">
                                        <a href="{{ route('admin.editexpert', ['id'=>$item->id]) }}"><button type="button" class="btn flat f-success btn-block fnt-xxs text-center " >ویرایش</button></a>
                                        {{-- <span class="fnt-xxs fnt-code">f-success</span> --}}
                                    </div>

                                @if ($item->blue_tick)
                                    <div class="c-grey text-center">
                                        <form action="{{ route('admin.unsetExpertsBlue', ['id'=>$item->id]) }}" method="post">
                                            {{-- @method('DELETE') --}}
                                            @csrf
                                            <input  class="btn flat f-info btn-block fnt-xxs text-center" type="submit" value="برداشت تیک آبی" />
                                         </form>
                                    </div>
                                @endif    
                                
                                <div class="c-grey text-center">
                                        <form action="{{ route('admin.activedeactiveexpert', ['id'=>$item->id]) }}" method="post">
                                            {{-- @method('DELETE') --}}
                                            @csrf
                                            <input  class="{{!$item->is_activ ? "btn flat f-danger btn-block fnt-xxs text-center" : "btn flat f-danger btn-block fnt-xxs text-center"}}" type="submit" value="{{!$item->is_active == true ? "فعال" : "غیرفعال"}}" />
                                         </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        
                    </tbody>
                </table>
	{{ $experts->links() }}

                <div class="add" id="add">
                <h4 class="c-grey  pt-3 pb-3 add">افزودن متخصص</h4>
                <hr class="mt-0 mb-4">
                <form action="{{ route('admin.addexpert') }}" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <div class="mb-3">
                        <label for="phone_number" class="form-label">شماره تلفن همراه:</label>
                        <input type="text" id="phone_number" name="phone_number" class="form-control" required>
                    </div>
                    @error('phone_number')
                        <div class="d-block text-danger">
                            {{$message}}
                        </div>
                    @enderror
                    <div class="mb-3">
                        <label for="first_name" class="form-label">نام:</label>
                        <input type="text" id="first_name" name="first_name" class="form-control" required>
                    </div>
                    @error('first_name')
                        <div class="d-block text-danger">
                            {{$message}}
                        </div>
                    @enderror
                    <div class="mb-3">
                        <label for="last_name" class="form-label">نام خانوادگی:</label>
                        <input type="text" id="last_name" name="last_name" class="form-control" required>
                    </div>
                    @error('last_name')
                        <div class="d-block text-danger">
                            {{$message}}
                        </div>
                    @enderror
                    <div class="mb-3">
                        <label for="national_id" class="form-label">کدملی:</label>
                        <input type="text" id="national_id" name="national_id" class="form-control" required>
                    </div>
                    @error('last_name')
                        <div class="d-block text-danger">
                            {{$message}}
                        </div>
                    @enderror
                    <div class="mb-3">
                        <label for="birth_date" class="form-label">تاریخ تولد:</label>
                        {{-- <input type="date" id="birth_date" name="birth_date" class="" onclick="showDate()" data-jdp-only-date autocomplete="off" > --}}
                        <input type="text" id="birth_date" name="birth_date" class="form-control" required data-jdp-only-date onclick="showDate()">

                    </div>
                    @error('birth_date')
                        <div class="d-block text-danger">
                            {{$message}}
                        </div>
                    @enderror
                    <div class="mb-3">
                        <label for="type" class="form-label">نوع متخصص:</label>
                        <select id="type" name="type" class="form-control" required>
                            <option value="business_unit">واحد صنفی</option>
                            <option value="self_employed">خویش فرما</option>
                            <option value="company">شرکت</option>
                        </select>
                    </div>
                    @error('type')
                        <div class="d-block text-danger">
                            {{$message}}
                        </div>
                    @enderror
                    <div id="additional_fields"></div>
                    <div class="mb-3">
                        <label for="address" class="form-label">آدرس:</label>
                        <input type="text" id="address" name="address" class="form-control" required>
                    </div>
                    @error('address')
                        <div class="d-block text-danger">
                            {{$message}}
                        </div>
                    @enderror
                    <div class="mb-3">
                        <label for="province" class="form-label">استان:</label>
                        <input type="text" id="province" name="province" class="form-control" required>
                    </div>
                    @error('province')
                        <div class="d-block text-danger">
                            {{$message}}
                        </div>
                    @enderror
                    <div class="mb-3">
                        <label for="city" class="form-label">شهر:</label>
                        <input type="text" id="city" name="city" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="map_coordinates" class="form-label">Map Lat:</label>
                        <input type="number" class="form-control " id="lat" name="lat"  min="1" step="any">
                    </div>
                    @error('lat')
                        <div class="d-block text-danger">
                            {{$message}}
                        </div>
                    @enderror
                    <div class="mb-3">
                        <label for="map_coordinates" class="form-label">Map Log:</label>
                        <input type="number" class="form-control " id="log" name="log"  min="1" step="any">
                    </div>
                    @error('log')
                        <div class="d-block text-danger">
                            {{$message}}
                        </div>
                    @enderror
                    <div class="mb-3">
                        <label for="is_active" class="form-label">وضعیت:</label>
                        <select id="is_active" name="is_active" class="form-control" required>
                            <option value="1">فعال</option>
                            <option value="0">غیرفعال</option>
                        </select>
                    </div>
                    @error('is_active')
                        <div class="d-block text-danger">
                            {{$message}}
                        </div>
                    @enderror
                    <div class="mb-3">
                        <label for="telegram_link" class="form-label">لینک تلگرام:</label>
                        <input type="text" id="telegram_link" name="telegram_link" class="form-control">
                    </div>
                    @error('telegram_link')
                        <div class="d-block text-danger">
                            {{$message}}
                        </div>
                    @enderror
                    <div class="mb-3">
                        <label for="whatsapp_link" class="form-label">لینک واتساپ:</label>
                        <input type="text" id="whatsapp_link" name="whatsapp_link" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="eitaa_link" class="form-label">لینک ایتا:</label>
                        <input type="text" id="eitaa_link" name="eitaa_link" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="services" class="form-label">خدمات :</label>
                        <select id="services" name="services[]" class="form-control" multiple>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}">{{ $service->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('services[]')
                        <div class="d-block text-danger">
                            {{$message}}
                        </div>
                    @enderror
                    <div class="mb-3">
                        <label for="gallery" class="form-label">گالری تصاویر:</label>
                        <input type="file" id="gallery" name="gallery[]" class="form-control" multiple>
                    </div>
                    @error('gallery[]')
                        <div class="d-block text-danger">
                            {{$message}}
                        </div>
                    @enderror

                    <div class="mb-3">
                        <label for="profile_image" class="form-label">تصویر پروفایل:</label>
                        <input type="file" id="profile_image" name="profile_image" class="form-control" >
                    </div>
                    @error('profile_image')
                        <div class="d-block text-danger">
                            {{$message}}
                        </div>
			@enderror
			<div class="mb-3">
    			<label for="exampleFormControlTextarea1" class="bmd-label-static">درباره ی من :</label>
 			   <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="about_me" ></textarea>
    @error('about_me')
        <div class="d-block text-danger">
            {{$message}}
        </div>
    	@enderror
	</div>

	<div class="mb-3">
    		<label for="guarantee" class="form-label">گارانتی</label>
    		<input type="text" id="guarantee" name="guarantee" class="form-control">
	</div>
    @error('guarantee')
    <div class="d-block text-danger">
        {{$message}}
    </div>
    @enderror

    	<div class="mb-3">
    		<label for="website_link" class="form-label">لینک سایت</label>
    		<input type="text" id="website_link" name="website_link" class="form-control">
	</div>
	@error('website_link')
    	<div class="d-block text-danger">
        	{{$message}}
    	</div>
	@enderror
                    <button type="submit" class="btn btn-primary">ایجاد متخصص</button>
                </form>
                </div>
            </div>
        </div>
</div>



</div>
</div>



@endsection

@section('script')
<script>
    document.getElementById('type').addEventListener('change', function () {
        let additionalFields = document.getElementById('additional_fields');
        additionalFields.innerHTML = '';
        let type = this.value;
    
        if (type === 'business_unit' || type === 'self_employed') {
            additionalFields.innerHTML += `
                <div class="mb-3">
                    <label for="national_id_photo" class="form-label">تصویر کارت ملی یا شناسنامه:</label>
                    <input type="file" id="national_id_photo" name="documents[national_id_photo]" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="personal_photo" class="form-label">تصویر پرسنلی (جدید):</label>
                    <input type="file" id="personal_photo" name="documents[personal_photo]" class="form-control">
                </div>
            `;
            if (type === 'business_unit') {
                additionalFields.innerHTML += `
                    <div class="mb-3">
                        <label for="business_license_photo" class="form-label">تصویر جواز کسب:</label>
                        <input type="file" id="business_license_photo" name="documents[business_license_photo]" class="form-control">
                    </div>
                `;
            }
        } else if (type === 'company') {
            additionalFields.innerHTML += `
                <div class="mb-3">
                    <label for="company_name" class="form-label">نام شرکت یا موسسه:</label>
                    <input type="text" id="company_name" name="company_name" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="registration_number" class="form-label">شماره ثبت:</label>
                    <input type="text" id="registration_number" name="registration_number" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="establishment_photo" class="form-label">تصویر آگهی تاسیس:</label>
                    <input type="file" id="establishment_photo" name="documents[establishment_photo]" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="changes_photo" class="form-label">تصویر آگهی آخرین تغییرات:</label>
                    <input type="file" id="changes_photo" name="documents[changes_photo]" class="form-control">
                </div>
            `;
        }
    });
    jalaliDatepicker.startWatch();
    function showDate(){
        jalaliDatepicker.show(document.getElementById("birth_date"), { placeholder: "yyyy-MM-dd" })
    }
</script>
<script>
    
</script>
@endsection
