@extends('layout.admin')
@section('title')
    ویرایش کیف پول 
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
                
                    <h4 class="c-grey  pt-3 pb-3">ویرایش کیف پول {{ $wallet->expert->first_name }} {{ $wallet->expert->last_name }}</h4>
                    <hr class="mt-0 mb-4">
                    <form class="p-2" action="{{ route('admin.walletEditHandller', ['id'=>$wallet->id]) }}" method="POST" enctype="multipart/form-data" id="serviceForm">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            

                                <div class="form-row ">
                                    <div class="col-4">
                                        <label for="exampleFormControlSelect1" class="bmd-label-static">موجودی</label>
                                        {{-- <label class="sr-only" for="inlineFormInput">Name</label> --}}
                                        <input type="number" class="form-control " id="balance" name="balance" min="0"  step="any" placeholder="موجودی" value="{{$wallet->balance}}">
                                        {{-- <input type="number" required name="price" min="0" value="0" step="any"> --}}
                                        @error('balance')
                                        <div class="d-block text-danger">
                                            {{$message}}
                                        </div>
                                         @enderror
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

