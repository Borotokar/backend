@extends('layout.admin')
@section('title')
    لیست خدمات بروتوکار
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
            <div class="card-body">
                
                <div class="row mb-3">
                    <h5 class="card-title">لیست خدمات برو‌توکار</h5>
			<a href="#addService"><button type="button" class="btn flat f-success btn-block fnt-xxs text-center " >ﺎﻓﺯﻭﺪﻧ</button></a>    




       </div>             
	<div style="width: 10px"></div>
                        <form action="{{ route('admin.services.search') }}" method="GET">
        <div class="input-group mb-6">
        <input type="text" name="search" class="form-control" placeholder="جستوجو خدمات" value="{{ request('search') }}" aria-describedby="button-addon2">
        <button class="btn btn-primary" type="submit">جستوجو</button>
         </div>
        </form>



              

                <hr>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">ردیف</th>
                            <th scope="col">تصویر</th>
                            <th scope="col">نام خدمت</th>
                            <th scope="col">شرح خدمت</th>
                            <th scope="col">دسته‌بندی</th>
                            <th scope="col">نوع</th>
                            <th scope="col">قیمت (کمیسیون)</th>
                            <th scope="col">سوالات</th>
                            <th scope="col">عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($services as $item)
                            <tr>
                                <th scope="row">{{$loop->iteration }}</th>
                                <td> <img src="{{URL::asset($item['image'])}}" alt="profile Pic" height="50" width="50"></td>
                                <td>{{$item['title']}}</td>
                                <td><p>{{Str::limit($item['des'], $limit = 50, $end = '...')}}</p></td>
                                <td>{{$item->servicecategories->first()->name ?? 'NONE'}}</td>
                                <td>{{$item->servicecategories->first()->types->name ?? 'NONE'}}</td>
                                <td>{{$item['commission']}} ريال</td>
                                <td>{{count($item->questions) ?? 'NONE'}} سوال</td>
                                <td>
                                    <div class="c-grey text-center  ">
                                        <a href="{{ route('admin.editservice', ['id'=>$item['id']]) }}"><button type="button" class="btn flat f-success btn-block fnt-xxs text-center " >ویرایش</button></a>
                                        {{-- <span class="fnt-xxs fnt-code">f-success</span> --}}
                                    </div>
				
	                          <div class="c-grey text-center">
                                     <form action="{{ route('admin.services.toggle-status', $item->id) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" 
                                                                class="btn flat  fnt-xxs text-center {{ $item->is_active ? 'btn-danger' : 'btn-success' }}">
                                                            {{ $item->is_active ? 'غیرفعال کردن' : 'فعال کردن' }}
                                                        </button>
                                        </form>
                                        <form id="deleteOrderForm-{{ $item->id }}" action="{{ route('admin.delleteservice', ['id' => $item->id]) }}" method="post" onsubmit="return confirmDelete(event, {{ $item->id }})">
                                            @csrf
                                            <input class="btn flat f-danger btn-block fnt-xxs text-center" type="submit" value="حذف" />
                                        </form>
                                    </div>	
                                </td>
                            </tr>
                        @endforeach
                        


                    </tbody>
		</table>
		{{ $services->links() }}
            </div>

            
            <div class="jumbotron shade pt-5 addService" id="addService">
                
                        <h4 class="c-grey  pt-3 pb-3">افزودن خدمت</h4>
                        <hr class="mt-0 mb-4">
                        <form class="p-2" action="{{ route('admin.addservicehandller') }}" method="POST" enctype="multipart/form-data" id="serviceForm">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                

                                    <div class="form-row ">
                                        <div class="col-5">
                                            <label for="exampleFormControlSelect1" class="bmd-label-static">نام خدمت</label>
                                            {{-- <label class="sr-only" for="inlineFormInput">Name</label> --}}
                                            <input type="text" class="form-control " id="title" name="title" placeholder="نام خدمت را وارد کنید">
                                            @error('title')
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
                            <div class="form-row ">
                                <div class="col-5">
                                    <label for="exampleFormControlSelect1" class="bmd-label-static">قیمت (کمیسیون)</label>
                                    {{-- <label class="sr-only" for="inlineFormInput">Name</label> --}}
                                    <input type="number" class="form-control " id="commission" name="commission" placeholder="قیمت (کمیسیون) خدمت ريال" min="1" step="any">
                                    @error('commission')
                                    <div class="d-block text-danger">
                                        {{$message}}
                                    </div>
                                     @enderror
                                </div>    
                                
                                <div class="col-5">
                                    <div class="form-group bmd-form-group is-filled">
                                        <label for="exampleFormControlSelect1" class="bmd-label-static">دسته بندی</label>
                                        <select class="form-control" id="exampleFormControlSelect1" name="category">
                                            @foreach ($cats as $cat)
                                            <option value="{{$cat->id}}"><p>
                                                {{$cat->name ?? "خالی"}} ({{$cat->types->name ?? "خالی"}})     
                                            </p></option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('category')
                                        <div class="d-block text-danger">
                                            {{$message}}
                                        </div>
                                        @enderror
                                    </div>
                            </div>
                  
                    </div>
                            <div class="form-group bmd-form-group">
                                <label for="exampleFormControlTextarea1" class="bmd-label-static">توضیحات خدمت</label>
                                <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="des"></textarea>
                                @error('des')
                                                <div class="d-block text-danger">
                                                    {{$message}}
                                                </div>
                                @enderror
                            </div>

                            <div id="questions">
                                <h4>سوالات:</h4>
                                <button type="button" class="btn btn-primary mb-3" onclick="addQuestion()">اضافه کردن سوال</button>
                            </div>

                            @error('questions')
                                <div class="d-block text-danger">
                                    {{$message}}
                                </div>
                            @enderror
            <div class="form-group">
                <label for="proposal_types">نوع‌های پیشنهاد</label>
                <select class="form-control" id="proposal_types" name="proposal_types[]" multiple>
                    @foreach($proposalTypes   as   $type)
                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                    @endforeach
                </select>
            </div>
                            <input type="submit" class="btn btn-primary btn-sm btn-block" value="افزدون">

                        </form>

                        <template id="question-template">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">سوال:</label>
                                        <input type="text" name="questions[__INDEX__][text]" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">نوع سوال:</label>
                                        <select name="questions[__INDEX__][type]" class="form-select" onchange="toggleAnswers(this)" required>
                                            <option value="" disabled selected>انتخاب کنید</option>
                                            <option value="predefined">پاسخ‌های از پیش تعیین شده</option>
                                            <option value="user">پاسخ کاربر</option>
                                        </select>
                                    </div>
                                    <div class="answers" style="display: none;">
                                        <h5>پاسخ‌ها:</h5>
                                        <button type="button" class="btn btn-secondary mb-3" onclick="addAnswer(this)">اضافه کردن پاسخ</button>
                                    </div>
                                    <button type="button" class="btn btn-danger" onclick="removeQuestion(this)">حذف سوال</button>
                                </div>
                            </div>
                        </template>
                    
                        <template id="answer-template">
                            <div class="mb-3">
                                <div class="input-group">
                                    <input type="text" name="questions[__INDEX__][answers][]" class="form-control" required>
                                    <button type="button" class="btn btn-danger" onclick="removeAnswer(this)">حذف پاسخ</button>
                                </div>
                            </div>
                        </template>
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
    let questionIndex = 0;

    function addQuestion() {
        let template = document.getElementById('question-template').innerHTML;
        document.getElementById('questions').insertAdjacentHTML('beforeend', template.replace(/__INDEX__/g, questionIndex));
        questionIndex++;
    }

    function toggleAnswers(select) {
        let answersDiv = select.parentElement.parentElement.querySelector('.answers');
        if (select.value === 'predefined') {
            answersDiv.style.display = 'block';
        } else {
            answersDiv.style.display = 'none';
        }
    }

    function addAnswer(button) {
        let template = document.getElementById('answer-template').innerHTML;
        let answersDiv = button.parentElement;
        let questionIndex = answersDiv.parentElement.parentElement.querySelector('select[name$="[type]"]').name.match(/\d+/)[0];
        answersDiv.insertAdjacentHTML('beforeend', template.replace(/__INDEX__/g, questionIndex));
    }

    function removeQuestion(button) {
        button.closest('.card').remove();
    }

    function removeAnswer(button) {
        button.closest('.mb-3').remove();
    }
</script>
{{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> --}}
<script>
    function confirmDelete(event, id) {
        event.preventDefault(); // جلوگیری از ارسال فرم
        if (confirm("آیا مطمئن هستید که می‌خواهید این مورد را حذف کنید؟")) {
            document.getElementById(`deleteOrderForm-${id}`).submit(); // ارسال فرم در صورت تأیید
        }
    }
</script>
@endsection
