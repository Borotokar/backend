@extends('layout.admin')
@section('title')
    لیست تراکنش های  بروتوکار
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
           <form action="{{ route('admin.transaction.search') }}" method="GET">
            <div class="input-group mb-6">
            <input type="text" name="search" class="form-control" placeholder="جستوجو" value="{{ request('search') }}" aria-describedby="button-addon2">
            <button class="btn btn-primary" type="submit">جستوجو</button>
            </div>
            </form>	      
                <div class="row">
                    <h5 class="card-title">لیست تراکنش  برو‌توکار</h5>
                    <div style="width: 20px"></div>
                    {{-- <a href="#addService"><button type="button" class="btn flat f-success btn-block fnt-xxs text-center " >افزودن</button></a> --}}
                </div>
                <hr>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">ردیف</th>
			    <th scope="col">نام کاربر</th>
			    <th scope="col">شماره موبایل </th>
			    <th scope="col">شماره فاکتور</th>			 
                            <th scope="col">مبلغ (بدون مالیات)</th>
			    <th scope="col">تاریخ</th>
    			    <th scope="col">وضعیت</th>
                        </tr>
                    </thead>

			    <tbody>
				@foreach ($transactions as $item)
				    <tr>
					<th scope="row">{{$loop->iteration }}</th>
					<td>{{$item->expert->first_name .' '. $item->expert->last_name}}</td>
				<td>{{$item->expert->phone_number}}</td>
                                <td>{{$item->transaction_id}}</td>
                                <td>{{ number_format($item->amount)}} ریال</td>
				<td>{{ \Morilog\Jalali\Jalalian::forge($item->created_at)->format('Y-m-d') }}</td>
				<td>{{$item->status == "pending" ? "درانتظار پرداخت" : ($item->status == "completed" ? "پرداخت شده" : "لغو شده")}}</td>

                            </tr>
                        @endforeach
                        


                    </tbody>
                </table>

                
</div>

 </div>
 </div>
{{-- </div> --}}

@endsection
