@extends('layout.admin')
@section('title')
    لیست نوع خدمات بروتوکار
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

                <div class="jumbotron shade pt-5 addService" id="addService">
                
                    <h4 class="c-grey  pt-3 pb-3">ویرایش نوع</h4>
                    <hr class="mt-0 mb-4">
                    <form class="p-2" action="{{ route('admin.updatetypes', ['id'=>$type->id]) }}" method="POST" enctype="multipart/form-data" id="serviceForm">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            

                                <div class="form-row ">
                                    <div class="col-4">
                                        <label for="exampleFormControlSelect1" class="bmd-label-static">نام نوع</label>
                                        {{-- <label class="sr-only" for="inlineFormInput">Name</label> --}}
                                        <input type="text" class="form-control " id="name" name="name" placeholder="نام نوع را وارد کنید" value="{{$type->name}}">
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
                        
                        </div>
                        
              
                </div>
                <br>
                        <input type="submit" class="btn btn-primary btn-sm btn-block" value="ویرایش">

                    </form>
                </div>
            </div>
        </div>
</div>



@endsection
