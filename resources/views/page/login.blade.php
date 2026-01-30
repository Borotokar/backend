@extends('layout.auth')
@section('title')
    صفحه ی ورود
@endsection
@section('form')
							@if (Session::get('fail'))
								
								<div class="alert alert-forth alert-shade alert-dismissible " role="alert">
									{{Session::get('fail')}}
									<button type="button" class="close" data-dismiss="alert" aria-label="Close">
										<span aria-hidden="true">×</span>
									</button>
								</div>
							@endif
							<form action="/admin/login_handller" method="POST">
								@csrf
								@error('login_id')
									<div class="d-block text-danger">
										{{$message}}
									</div>
								@enderror
								<div class="form-group m-0 bmd-form-group">
									<label for="exampleInputEmail1" class="bmd-label-static">شماره تلفن / نام کاربری</label>
									<div class="input-group custom ">
										<input
											type="text"
											class="form-control form-control-lg"
											placeholder="شماره تلفن / نام کاربری"
											name="login_id"
											value="{{old('login_id')}}"
										/>
									
								</div>

					
								@error('password')
									<div class="d-block text-danger">
										{{$message}}
									</div>
								@enderror
								
								<div class="form-group m-0 bmd-form-group">
									<label for="exampleInputPassword1" class="bmd-label-static">رمز عبور</label>
									<input
										type="password"
										class="form-control form-control-lg"
										placeholder="**********"
										name="password"
										id="password"
										/>
										<br>
									<input type="checkbox" onclick="myFunction()">  نمایش رمز
				
				
								
								
								</div>
								<br>
								
								<div class="row">
									<div class="col-sm-12">
										<div class="input-group mb-0">
											<!--
											use code for form submit
										-->
										<input class="btn btn-primary btn-lg btn-block" type="submit" value="ورود">
										
										</div>
										
									</div>
								</div>
							</form>


@endsection

@section('script')
{{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script> --}}

<script>
function myFunction() {
  var x = document.getElementById("password");
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }
}
</script>
@endsection