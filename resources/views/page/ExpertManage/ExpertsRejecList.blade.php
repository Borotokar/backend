@extends('layout.admin')
@section('title')
    لیست متخصصین رد شده بروتوکار
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
             <form action="{{ route('admin.expertn.search') }}" method="GET">
            <div class="input-group mb-6">
            <input type="text" name="search" class="form-control" placeholder="جستوجو متخصصین" value="{{ request('search') }}" aria-describedby="button-addon2">
            <button class="btn btn-primary" type="submit">جستوجو</button>
            </div>
            </form>		
                <div class="row">
                    <h5 class="card-title">لیست متخصصین رد شده برو‌توکار</h5>
                    <div style="width: 20px"></div>
                    <a href="#add"><button type="button" class="btn flat f-success btn-block fnt-xxs text-center " >افزودن</button></a>
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
                                        <a href="{{ route('admin.expertsaccess', ['id'=>$item->id]) }}"><button type="button" class="btn flat f-success btn-block fnt-xxs text-center " >مشاهده و تایید هویت</button></a>
                                        {{-- <span class="fnt-xxs fnt-code">f-success</span> --}}
                                    </div>

                                    <div class="c-grey text-center">
                                        
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        


                    </tbody>
                </table>



            </div>
        </div>
</div>



@endsection


