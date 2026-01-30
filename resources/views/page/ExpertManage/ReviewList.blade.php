@extends('layout.admin')
@section('title')
    لیست نظرات بروتوکار
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
	    <form action="{{ route('admin.review.search') }}" method="GET">
            <div class="input-group mb-6">
            <input type="text" name="search" class="form-control" placeholder="جستوجو " value="{{ request('search') }}" aria-describedby="button-addon2">
            <button class="btn btn-primary" type="submit">جستوجو</button>
            </div>
            </form>		
                <div class="row">
                    <h5 class="card-title">لیست نظرات برو‌توکار</h5>
                    <div style="width: 20px"></div>
                    {{-- <a href="#addService"><button type="button" class="btn flat f-success btn-block fnt-xxs text-center " >افزودن</button></a> --}}
                </div>
                <hr>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">ردیف</th>
                            <th scope="col">نام کاربر</th>
                            <th scope="col">نام متخصص</th>
                            <th scope="col">نام خدمت انجام شده</th>
                            <th scope="col">نظر</th>
                            <th scope="col">امتیاز</th>
                            <th scope="col">وضعیت</th>
                            <th scope="col">عملیات</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($reviews as $item)
                            <tr>
                                <th scope="row">{{$loop->iteration }}</th>
                                <td>{{$item->user->name}}</td>
                                <td>{{$item->expert->first_name}}</td>
                                <td>{{$item->order->service->title}}</td>
                                <td>{{$item->comment}}</td>
                                <td>{{$item->rating}}</td>
                                <td>{{$item->is_active ? "تایید شده" : "در انتظار تایید"}}</td>
                                <td>

                                    <div class="c-grey text-center">
                                        <form action="{{ route('admin.accessreview', ['id'=>$item->id]) }}" method="post">
                                            {{-- @method('DELETE') --}}
                                            @csrf
                                            <input  class="{{$item->is_active ? "btn flat f-danger btn-block fnt-xxs text-center" : "btn flat f-success btn-block fnt-xxs text-center "}}" type="submit" value="{{$item->is_active  ? "غیرفعال" : "تایید"}}" />
                                         </form>
                                    </div>

                                    <div class="c-grey text-center  ">
                                        {{-- <a href="{{ route('admin.accessreview', ['id'=>$item->id]) }}"><button type="button" class="btn flat f-danger btn-block fnt-xxs text-center " >حذف</button></a>
                                        <span class="fnt-xxs fnt-code">f-success</span> --}}
                                        <form action="{{ route('admin.deletereview', ['id'=>$item->id]) }}" method="post">
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
