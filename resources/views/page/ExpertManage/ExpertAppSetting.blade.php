@extends('layout.admin')
@section('title')
    ویرایش اپ متخصص بروتوکار
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
                
                <div class="row">
                    <h5 class="card-title">ویرایش اپ متخصص</h5>
                    <div style="width: 20px"></div>
                    {{-- <a href="#addService"><button type="button" class="btn flat f-success btn-block fnt-xxs text-center " >افزودن</button></a> --}}
                </div>
                <hr>

                <form action="{{ route('admin.expertappsettingupdate') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="form-group bmd-form-group">
                        <label for="exampleFormControlTextarea1" class="bmd-label-static">شرایط و قوانین</label>
                        <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="law" >{{$setting->law}}</textarea>
                        @error('law')
                                        <div class="d-block text-danger">
                                            {{$message}}
                                        </div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-success">ذخیره</button>
                </form>

            </div>
        </div>
</div>


</div>
</div>



@endsection
