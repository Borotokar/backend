@extends('layout.admin')
@section('title')
    لیست کیف پول متخصصین بروتوکار
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
             <form action="{{ route('admin.wallet.search') }}" method="GET">
            <div class="input-group mb-6">
            <input type="text" name="search" class="form-control" placeholder="جستوجو" value="{{ request('search') }}" aria-describedby="button-addon2">
            <button class="btn btn-primary" type="submit">جستوجو</button>
            </div>
            </form>  
                <div class="row">
                    <h5 class="card-title">لیست کیف پول متخصصین </h5>
                    <div style="width: 20px"></div>
                    {{-- <a href="#addService"><button type="button" class="btn flat f-success btn-block fnt-xxs text-center " >افزودن</button></a> --}}
                </div>
                <hr>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">ردیف</th>
			    <th scope="col">نام متخصص</th>
                            <th scope="col">کدملی</th>
                            <th scope="col">شماره موبایل</th>
                            <th scope="col">موجودی</th>
                            <th scope="col">عملیات</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($wallets as $item)
                            <tr>
                                <th scope="row">{{$loop->iteration }}</th>
                              <td>{{$item->expert->first_name}} {{$item->expert->last_name}}</td>
			     <td>{{$item->expert->national_id}}</td>
			     <td>{{$item->expert->phone_number}}</td>
			      <td>{{ number_format($item->balance) }} ریال</td>
                                <td>
                                    <div class="c-grey text-center  ">
                                        <a href="{{ route('admin.walletEdit', ['id'=>$item->id]) }}"><button type="button" class="btn flat f-success btn-block fnt-xxs text-center " >ویرایش</button></a>
                                        {{-- <span class="fnt-xxs fnt-code">f-success</span> --}}
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
{{-- </div> --}}

@endsection

@section('script')

@endsection
