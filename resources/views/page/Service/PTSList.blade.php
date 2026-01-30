@extends('layout.admin')
@section('title')
    لیست نوع پیشنهاد خدمات بروتوکار
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
                    <h5 class="card-title">لیست نوع  پیشنهاد خدمات برو‌توکار</h5>
                    <div style="width: 20px"></div>
                    <a href="#addService"><button type="button" class="btn flat f-success btn-block fnt-xxs text-center " >افزودن</button></a>
                </div>
                <hr>


	<h1>نوع‌های پیشنهاد</h1>

        

        <table class="table">
            <thead>
                <tr>
                    <th>نام</th>
                    <th>عملیات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($proposalTypes as $type)
                    <tr>
                        <td>{{ $type->name }}</td>
                        <td>
                            <a href="{{ route('admin.proposal_types.edit', $type->id) }}" ><button  class="btn flat f-success btn-block fnt-xxs text-center">ویرایش</button> </a>

	<div class="c-grey text-center">
    		<form id="deleteOrderForm-{{ $type->id }}" action="{{ route('admin.proposal_types.destroy', ['id' => $type->id]) }}" method="post" onsubmit="return confirmDelete(event, {{ $type->id }})">
        	@csrf
        	<input class="btn flat f-danger btn-block fnt-xxs text-center" type="submit" value="حذف" />
    		</form>
	</div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>	


                <div class="jumbotron shade pt-5 addService" id="addService">
                
                    <h4 class="c-grey  pt-3 pb-3">افزودن نوع</h4>
                    <hr class="mt-0 mb-4">
                    <form class="p-2" action="{{ route('admin.proposal_types.store') }}" method="POST" enctype="multipart/form-data" id="serviceForm">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            

                                <div class="form-row ">
                                    <div class="col-4">
                                        <label for="exampleFormControlSelect1" class="bmd-label-static">نام نوع</label>
                                        {{-- <label class="sr-only" for="inlineFormInput">Name</label> --}}
                                        <input type="text" class="form-control " id="name" name="name" placeholder="نام نوع را وارد کنید">
                                        @error('name')
                                        <div class="d-block text-danger">
                                            {{$message}}
                                        </div>
                                         @enderror
                                    </div>
                                    </div>
                        
                        </div>
                        
              
                </div>
                    <br>
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


