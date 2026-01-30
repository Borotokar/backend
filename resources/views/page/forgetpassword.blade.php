@extends('layout.auth')
@section('title')
    Login Admin
@endsection
@section('body')
<div class="container">
				<div class="row align-items-center">
					<div class="col-md-6">
						<img src="vendors/images/forgot-password.png" alt="">
					</div>
					<div class="col-md-6">
						<div class="login-box bg-white box-shadow border-radius-10">
							<div class="login-title">
								<h2 class="text-center text-primary">Forgot Password</h2>
							</div>
							@if (Session::get('fail'))
								<div class="alert alert-danger">
									{{Session::get('fail')}}
									<button type="button" class="Close" data-dismiss="alert" aria-label="Close">
										<span aria-hddden="true">&times;</span>
									</button>
								</div>
							@endif
                            <h6 class="mb-20">
								Enter your phone address to reset your password
							</h6>
                            
							<form>
								<div class="input-group custom">
									<input type="text" class="form-control form-control-lg" placeholder="Phone">
									<div class="input-group-append custom">
										<span class="input-group-text"><i class="fa fa-envelope-o" aria-hidden="true"></i></span>
									</div>
								</div>
								<div class="row align-items-center">
									<div class="col-5">
										<div class="input-group mb-0">
											<!--
											use code for form submit
											<input class="btn btn-primary btn-lg btn-block" type="submit" value="Submit">
										-->
											<a class="btn btn-primary btn-lg btn-block" href="index.html">Submit</a>
										</div>
									</div>
									<div class="col-2">
										<div class="font-16 weight-600 text-center" data-color="#707373" style="color: rgb(112, 115, 115);">
											OR
										</div>
									</div>
									<div class="col-5">
										<div class="input-group mb-0">
											<a class="btn btn-outline-primary btn-lg btn-block" href="{{ route('admin.login') }}">Login</a>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
@endsection