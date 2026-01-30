@extends('layout.admin')
@section('title')
    ویرایش اپ کاربر بروتوکار
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
                    <h5 class="card-title">ویرایش اپ کاربر</h5>
                    <div style="width: 20px"></div>
                    {{-- <a href="#addService"><button type="button" class="btn flat f-success btn-block fnt-xxs text-center " >افزودن</button></a> --}}
                </div>
                <hr>

                <form action="{{ route('admin.userappsettingupdate') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-3">
                        <img src="{{URL::asset($setting->baneer1)}}" alt="profile Pic" height="50" width="50">
                        <label for="banner1" class="form-label">بنر 1:</label>
                        <div class="custom-file" dir="ltr">
                            <label class="custom-file-label" for="inputGroupFile01" style="text-align: left">انتخاب کنید</label>
                            <input  type="file" class="custom-file-input" id="inputGroupFile01" name="baneer1" aria-describedby="inputGroupFileAddon01">
                          </div>
                    </div>

                    @error('baneer1')
                        <div class="d-block text-danger">
                            {{$message}}
                        </div>
                    @enderror

                    <div class="mb-3">
                        <img src="{{URL::asset($setting->baneer2)}}" alt="profile Pic" height="50" width="50">
                        <label for="banner2" class="form-label">بنر 2:</label>
                        <div class="custom-file" dir="ltr">
                            <label class="custom-file-label" for="inputGroupFile01" style="text-align: left">انتخاب کنید</label>
                            <input  type="file" class="custom-file-input" id="inputGroupFile01" name="baneer2" aria-describedby="inputGroupFileAddon01">
                          </div>
                    </div>

                    @error('banner2')
                        <div class="d-block text-danger">
                            {{$message}}
                        </div>
                    @enderror

                    <div class="mb-3">
                        <img src="{{URL::asset($setting->baneer3)}}" alt="profile Pic" height="50" width="50">
                        <label for="banner3" class="form-label">بنر 3:</label>
                        <div class="custom-file" dir="ltr">
                            <label class="custom-file-label" for="inputGroupFile01" style="text-align: left">انتخاب کنید</label>
                            <input  type="file" class="custom-file-input" id="inputGroupFile01" name="baneer3" aria-describedby="inputGroupFileAddon01">
                          </div>
                    </div>

                    @error('banner3')
                        <div class="d-block text-danger">
                            {{$message}}
                        </div>
                    @enderror

                    <div class="mb-3">
                        <img src="{{URL::asset($setting->baneer4)}}" alt="profile Pic" height="50" width="50">
                        <label for="picture" class="form-label">بنر 4:</label>
                        <div class="custom-file" dir="ltr">
                            <label class="custom-file-label" for="inputGroupFile01" style="text-align: left">انتخاب کنید</label>
                            <input  type="file" class="custom-file-input" id="inputGroupFile01" name="baneer4" aria-describedby="inputGroupFileAddon01">
                          </div>
                    </div>

                    @error('banner4')
                        <div class="d-block text-danger">
                            {{$message}}
                        </div>
                    @enderror

                    <div class="form-group bmd-form-group">
                        <label for="exampleFormControlTextarea1" class="bmd-label-static">شرایط و قوانین</label>
                        <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="law" >{{$setting->law}}</textarea>
                        @error('law')
                                        <div class="d-block text-danger">
                                            {{$message}}
                                        </div>
                        @enderror
		    </div>

                <!-- @foreach([1,2,3,4] as $i)
        <div class="form-group">
            <label for="expert_id{{ $i }}">متخصص شماره {{ $i }}</label>
            <select name="expert_id{{ $i }}" id="expert_id{{ $i }}" class="form-control">
                <option value="">-- انتخاب کنید --</option>
                @foreach($experts as $expert)
                    <option value="{{ $expert->id }}"
                        {{ $setting->{'expert_id'.$i} == $expert->id ? 'selected' : '' }}>
                        {{ $expert->first_name }} {{ $expert->last_name }}
                    </option>
                @endforeach
            </select>
        </div>
    @endforeach -->

    @foreach([1,2,3,4] as $i)
    <div class="form-group">
        <label for="expert_id{{ $i }}">متخصص شماره {{ $i }}</label>
        <select name="expert_id{{ $i }}" id="expert_id{{ $i }}" class="form-control select2">
            <option value="">-- انتخاب کنید --</option>
            @foreach($experts as $expert)
                <option value="{{ $expert->id }}"
                    {{ $setting->{'expert_id'.$i} == $expert->id ? 'selected' : '' }}>
                    {{ $expert->first_name }} {{ $expert->last_name }}
                </option>
            @endforeach
        </select>
    </div>
@endforeach

		<label for="categories">انتخاب دسته‌بندی‌ها (حداکثر 15 مورد):</label>
    		<select name="categories[]" id="categories" class="form-control" multiple>
        	@foreach($allCategories as $category)
            	   <option value="{{ $category->id }}" 
                	 {{ isset($setting->categories) && in_array($category->id, json_decode($setting->categories, true)) ? 'selected' : '' }}>
                	{{ $category->name }}
        	    </option>
        	@endforeach
    		</select> 

                    <button type="submit" class="btn btn-success">ذخیره</button>
                </form>

            </div>
        </div>
</div>


</div>
</div>



@endsection
@section('script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    // $(document).ready(function() {
    //     $('.select2').select2({
    //         placeholder: 'جستجوی متخصص...',
    //         allowClear: true,
    //         dir: "rtl",
    //         width: '100%'
    //     });
    // });

    $(document).ready(function() {
    $('.select2').select2();
    });
</script>

@endsection
