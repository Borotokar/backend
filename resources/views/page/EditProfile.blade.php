@extends('layout.admin')
@section('title')
     ویرایش پروفایل ادمین بروتوکار
@endsection
@section('body')
<div class="container-fluid ">

    <!-- content -->
    <!-- breadcrumb -->
    {{-- <div class="col-xs-1 col-sm-1 col-md-8 col-lg-8 "> --}}
        {{-- success --}}
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
            {{-- <div class="card-body">
                
                
                
            </div> --}}

            
            <div class="jumbotron shade pt-5 addService" id="addService">
                
                        <h4 class="c-grey  pt-3 pb-3">ویرایش پروفایل</h4>
                        <hr class="mt-0 mb-4">
                        <form class="p-2" action="{{ route('admin.editprofilehandler') }}" method="POST" enctype="multipart/form-data" id="serviceForm">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                
    
                                    <div class="form-row ">
                                        <div class="col-4">
                                            <label for="exampleFormControlSelect1" class="bmd-label-static">نام</label>
                                            {{-- <label class="sr-only" for="inlineFormInput">Name</label> --}}
                                            <input type="text" class="form-control " id="name" name="name"  value="{{$admin->name}}">
                                            @error('name')
                                            <div class="d-block text-danger">
                                                {{$message}}
                                            </div>
                                             @enderror
                                        </div>

                                        <div class="col-4">
                                            <label for="exampleFormControlSelect1" class="bmd-label-static">شماره تلفن</label>
                                            {{-- <label class="sr-only" for="inlineFormInput">Name</label> --}}
                                            <input type="text" class="form-control " id="name" name="phone_number"  value="{{$admin->phone_number}}">
                                            @error('phone_number')
                                            <div class="d-block text-danger">
                                                {{$message}}
                                            </div>
                                             @enderror
                                        </div>

                                        <div class="col-4">
                                            <label for="exampleFormControlSelect1" class="bmd-label-static">نام کاربری</label>
                                            {{-- <label class="sr-only" for="inlineFormInput">Name</label> --}}
                                            <input type="text" class="form-control " id="name" name="username"  value="{{$admin->username}}">
                                            @error('username')
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
                                            {{-- @error('type')
                                                <div class="d-block text-danger">
                                                    {{$message}}
                                                </div>
                                                @enderror --}}
                                            </div>
                            <br>
                                         <input type="submit" class="btn btn-primary btn-sm btn-block" value="ویرایش">
                            </div>
                        </div>
                        <div class="col-10">
                        </div>
                        </form>


                    </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
{{-- </div> --}}

@endsection

