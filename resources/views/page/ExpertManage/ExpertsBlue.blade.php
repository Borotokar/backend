@extends('layout.admin')
@section('title')
    لیست درخواست تیک آبی بروتوکار
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

                <div class="col-md-4">
                    <button class="btn btn-primary" type="submit">جستوجو</button>
                </div>
            </div>
        </form> 
        <div class="row">
            <h5 class="card-title">لیست درخواست تیک آبی</h5>
            <div style="width: 20px"></div>
            <!-- <a href="#add"><button type="button" class="btn flat f-success btn-block fnt-xxs text-center">افزودن</button></a> -->
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

                                    <div class="c-grey text-center">
                                        <form action="{{ route('admin.setExpertsBlue', ['id'=>$item->id]) }}" method="post">
                                            {{-- @method('DELETE') --}}
                                            @csrf
                                            <input  class="btn flat f-info btn-block fnt-xxs text-center" type="submit" value="اهدا تیک آبی" />
                                         </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        
                    </tbody>
                </table>
	{{ $experts->links() }}

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
