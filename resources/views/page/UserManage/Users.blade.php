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
        <form action="{{ route('admin.user.search') }}" method="GET">
            <div class="row">
                <div class="col-md-6">
                    <div class="input-group mb-6">
                        <input type="text" name="search" class="form-control" placeholder="جستجو کاربران" value="{{ request('search') }}" aria-describedby="button-addon2">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-control">
                        <option value="">همه وضعیت‌ها</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>فعال</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>غیرفعال</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-primary btn-block" type="submit">اعمال فیلتر</button>
                </div>
            </div>
        </form> 
                <hr>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">ردیف</th>
                            <th scope="col">تصویر</th>
                            <th scope="col">نام و نام خانوادگی</th>
                            <th scope="col">شماره تلفن</th>
                            <th scope="col">نام کاربری</th>
                            <th scope="col">جنسیت</th>
                            <th scope="col">وضعیت</th>
                            <th scope="col">عضویت</th>
                            <th scope="col">عملیات</th>
                        </tr>
                    </thead>

                    <tbody>
                        
			@foreach ($users as $user)
<tr>
    <td>{{ $loop->iteration }}</td>
    <td><img src="{{ asset($user->picture) }}" alt="profile Pic" height="50" width="50"></td>
    <td>{{ $user->name }}</td>
    <td>{{ $user->phone_number }}</td>
    <td>{{ $user->username }}</td>
    <td>{{ $user->sex == "Male" ? "آقا" : "خانم" }}</td>
    <td>{{ $user->status == "pending" ? "غیرفعال" : "فعال" }}</td>
    <td>{{Morilog\Jalali\Jalalian::fromCarbon($user->created_at)->format('Y/m/d');}}</td>
    <td>
	                                    <div class="c-grey text-center  ">
                                        <a href="{{ route('admin.useredit', ['id'=>$user->id]) }}"><button type="button" class="btn flat f-success btn-block fnt-xxs text-center " >ویرایش</button></a>
                                        {{-- <span class="fnt-xxs fnt-code">f-success</span> --}}
    				    </div>
                                        <div class="c-grey text-center">
                                        <form action="{{ route('admin.activedeactiveuser', ['id'=>$user->id]) }}" method="post">
                                            <!-- {{-- @method('DELETE') --}} -->
                                            @csrf
                                            <input  class="{{$user->status == "pending" ? "btn flat f-success btn-block fnt-xxs text-center " : "btn flat f-danger btn-block fnt-xxs text-center"}}" type="submit" value="{{$user->status == "pending" ? "فعال" : "غیرفعال"}}" />
                                         </form>    
                                    </div>	
	</td>
</tr>

@endforeach

                    </tbody>
                </table>
	    </div>
	{{ $users->links() }}
        </div>
</div>


</div>
</div>

@endsection
@section('script')
@endsection
