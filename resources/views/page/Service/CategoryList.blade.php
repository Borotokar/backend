@extends('layout.admin')
@section('title')
    لیست دسته‌بندی خدمات بروتوکار
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
            <form action="{{ route('admin.cats.search') }}" method="GET">
            <div class="input-group mb-6">
            <input type="text" name="search" class="form-control" placeholder="جستوجو دسته بندی" value="{{ request('search') }}" aria-describedby="button-addon2">
            <button class="btn btn-primary" type="submit">جستوجو</button>
            </div>
            </form>    
                <div class="row">
                    <h5 class="card-title">لیست دسته‌بندی خدمات برو‌توکار</h5>
                    <div style="width: 20px"></div>
                    <a href="#addService"><button type="button" class="btn flat f-success btn-block fnt-xxs text-center " >افزودن</button></a>
                </div>
                <hr>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">ردیف</th>
                            <th scope="col">تصویر</th>
			    <!-- <th scope="col">شعار</th> -->
			    <th scope="col">نام دسته بندی</th>
                            <th scope="col">نوع دسته بندی</th>
                            <th scope="col">عملیات</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($cats as $item)
                            <tr>
                                <th scope="row">{{$loop->iteration }}</th>
                                <td> <img src="{{URL::asset($item['image'])}}" alt="profile Pic" height="50" width="50"></td>
				<!-- <td>{{$item->slogan}}</td> -->
				<td>{{$item->name}}</td>
                                <td>{{$item->types->name ?? "نوع را وارد کنید"}}</td>
                                <td>
                                    <div class="c-grey text-center  ">
                                        <a href="{{ route('admin.editcategoris', ['id'=>$item->id]) }}"><button type="button" class="btn flat f-success btn-block fnt-xxs text-center " >ویرایش</button></a>
                                        {{-- <span class="fnt-xxs fnt-code">f-success</span> --}}
                                    </div>
				<div class="c-grey text-center">
                                        <form id="deleteOrderForm-{{ $item->id }}" action="{{ route('admin.deletecategoris', ['id' => $item->id]) }}" method="post" onsubmit="return confirmDelete(event, {{ $item->id }})">
                                            @csrf
                                            <input class="btn flat f-danger btn-block fnt-xxs text-center" type="submit" value="حذف" />
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        


                    </tbody>
                </table>
		{{ $cats->links() }}
                <div class="jumbotron shade pt-5 addService" id="addService">
                
                    <h4 class="c-grey  pt-3 pb-3">افزودن دسته‌بندی</h4>
                    <hr class="mt-0 mb-4">
                    <form class="p-2" action="{{ route('admin.addcategoris') }}" method="POST" enctype="multipart/form-data" id="serviceForm">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            

                                <div class="form-row ">
                                    <div class="col-4">
                                        <label for="exampleFormControlSelect1" class="bmd-label-static">نام دسته‌بندی</label>
                                        {{-- <label class="sr-only" for="inlineFormInput">Name</label> --}}
                                        <input type="text" class="form-control " id="name" name="name" placeholder="نام دسته‌بندی را وارد کنید">
                                        @error('name')
                                        <div class="d-block text-danger">
                                            {{$message}}
                                        </div>
                                         @enderror
                                    </div>

                                    <div class="col-5">
                                        <label for="exampleFormControlSelect1" class="bmd-label-static">تصویر</label>
                                        <div style="height: 6px"></div>
                                        <div class="custom-file">
                                            <div class="custom-file" dir="ltr">
                                                <label class="custom-file-label" for="inputGroupFile01" style="text-align: left">انتخاب کنید</label>
                                                <input  type="file" class="custom-file-input" id="inputGroupFile01" name="image" aria-describedby="inputGroupFileAddon01">
                                              </div>
                                              @error('image')
                                                <div class="d-block text-danger">
                                                    {{$message}}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-3">
                                        <div class="form-group bmd-form-group is-filled">
                                            <label for="exampleFormControlSelect1" class="bmd-label-static">نوع</label>
                                            <select class="form-control" id="exampleFormControlSelect1" name="type">
                                                @foreach ($types as $type)
                                                <option value="{{$type->id}}"><p>
                                                    {{$type->name}}     
                                                </p></option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('type')
                                            <div class="d-block text-danger">
                                                {{$message}}
                                            </div>
                                            @enderror
                                        </div>
                        
                        </div>
                        <div class="form-row "> 
                            
                            
                        </div>
              		<div class="col-4">
                                        <label for="exampleFormControlSelect1" class="bmd-label-static">شعار دسته بندی</label>
                                        {{-- <label class="sr-only" for="inlineFormInput">Name</label> --}}
                                        <input type="text" class="form-control " id="slogan" name="slogan" placeholder="شعار دسته بندی">
                                        @error('slogan')
                                        <div class="d-block text-danger">
                                            {{$message}}
                                        </div>
                                         @enderror
                                    </div>	
                </div>
                        

                        

                        @error('questions')
                            <div class="d-block text-danger">
                                {{$message}}
                            </div>
                        @enderror
                        <input type="submit" class="btn btn-primary btn-sm btn-block" value="افزدون">

                    </form>
                </div>
            </div>
        </div>
</div>

            </div>

        


 </div>
{{-- </div> --}}

@endsection

@section('script')
<script>
    function confirmDelete(event, id) {
        event.preventDefault(); // جلوگیری از ارسال فرم
        if (confirm("آیا مطمئن هستید که می‌خواهید این مورد را حذف کنید؟")) {
            document.getElementById(`deleteOrderForm-${id}`).submit(); // ارسال فرم در صورت تأیید
        }
    }
</script>
@endsection
