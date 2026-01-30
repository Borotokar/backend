@extends('layout.admin')
@section('title')
    لیست ادمین‌ها بروتوکار
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
                
                <!-- <div class="row">
                    <h5 class="card-title">لیست ادمین‌ها برو‌توکار</h5>
                    <div style="width: 20px"></div>
                    
                </div> -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title fw-bold mb-0">لیست ادمین‌ها برو‌توکار</h5>
                <a href="{{ route('admin.create') }}" class="btn btn-success btn-sm">
                    + افزودن ادمین
                </a>
            </div>
                <hr>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">ردیف</th>
                            <th scope="col">تصویر</th>
                            <th scope="col">نام و نام خانوادگی</th>
                            <th scope="col">شماره تلفن</th>
                            <th scope="col">نام کاربری</th>
                            <th scope="col">عملیات</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($admins as $item)
                            <tr>
                                <th scope="row">{{$loop->iteration }}</th>
                                <td> <img src="{{URL::asset($item['picture'])}}" alt="profile Pic" height="50" width="50"> </td>
                                <td>{{$item->name}}</td>
                                <td>{{$item->phone_number}}</td>
                                <td>{{$item->username}}</td>
                                <td>
                                    <div class="c-grey text-center  ">
                                        <a href="{{ route('admin.editAdmin', ['id'=>$item->id]) }}"><button type="button" class="btn flat f-success btn-block fnt-xxs text-center " >ویرایش</button></a>
                                        {{-- <span class="fnt-xxs fnt-code">f-success</span> --}}
                                    </div>

                                    <div class="c-grey text-center">
                                        <form action="{{ route('admin.AdminDelete', ['id'=>$item->id]) }}" method="post">
                                            {{-- @method('DELETE') --}}
                                            @csrf
                                            <input  class="btn flat f-danger btn-block fnt-xxs text-center" type="submit" value="حذف" />
                                         </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        


                    </tbody>
                </table>
            </div>
        </div>
</div>


</div>
</div>



@endsection

