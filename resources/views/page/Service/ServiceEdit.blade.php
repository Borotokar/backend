@extends('layout.admin')
@section('title')
     ویرایش خدمت بروتوکار
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
                
                        <h4 class="c-grey  pt-3 pb-3">ویرایش خدمت</h4>
                        <hr class="mt-0 mb-4">
                        <form class="p-2" action="{{ route('admin.updateService', [$service->id]) }}" method="POST" enctype="multipart/form-data" id="serviceForm">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                

                                    <div class="form-row ">
                                        <div class="col-5">
                                            <label for="exampleFormControlSelect1" class="bmd-label-static">نام خدمت</label>
                                            {{-- <label class="sr-only" for="inlineFormInput">Name</label> --}}
                                            <input type="text" class="form-control " id="title" name="title" placeholder="نام خدمت را وارد کنید" value="{{$service->title}}">
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
                                                    <input  type="file" class="custom-file-input" id="inputGroupFile01" name="image" aria-describedby="inputGroupFileAddon01" value="{{$service->image}}">
                                                  </div>
                                                  @error('image')
                                                    <div class="d-block text-danger">
                                                        {{$message}}
                                                    </div>
                                                @enderror
                                            </div>

                                        </div>
                                        <img src="{{URL::asset($service->image)}}" alt="profile Pic" height="100" width="100">

                            
                            </div>
                            <div class="form-row ">
                                <div class="col-5">
                                    <label for="exampleFormControlSelect1" class="bmd-label-static">قیمت (کمیسیون)</label>
                                    {{-- <label class="sr-only" for="inlineFormInput">Name</label> --}}
                                    <input type="number" class="form-control " id="commission" name="commission" placeholder="قیمت (کمیسیون) خدمت ريال" min="1" step="any" value="{{$service->commission}}">
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
                                            <option value="{{$service->servicecategories->first()->id}}" selected><p>
                                                {{$service->servicecategories->first()->name ?? "خالی"}} ({{$service->servicecategories->first()->types->name ?? "خالی" }})     
                                            </p></option>
                                            <br>
                                            @foreach ($cats as $cat)
                                            <option value="{{$cat->id}}"><p>
                                                {{$cat->name ?? "نام ندارد"}} ({{$cat->types->name ??"نام ندارد"}})     
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
                                <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="des" >{{$service->des}}</textarea>
                                @error('des')
                                                <div class="d-block text-danger">
                                                    {{$message}}
                                                </div>
                                @enderror
                            </div>

                            <div id="questions">
                                <h4>سوالات:</h4>
                                <button type="button" class="btn btn-primary mb-3" onclick="addQuestion()">اضافه کردن سوال</button>
                                @foreach($service->questions as $index => $question)
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label class="form-label">سوال:</label>
                                            <input type="text" name="questions[{{ $index }}][text]" class="form-control" value="{{ $question->question }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">نوع سوال:</label>
                                            <select name="questions[{{ $index }}][type]" class="form-select" onchange="toggleAnswers(this)" required>
                                                <option value="" disabled>انتخاب کنید</option>
                                                <option value="predefined" {{ $question->type == 'predefined' ? 'selected' : '' }}>پاسخ‌های از پیش تعیین شده</option>
                                                <option value="user" {{ $question->type == 'user' ? 'selected' : '' }}>پاسخ کاربر</option>
                                            </select>
                                        </div>
                                        <div class="answers" style="{{ $question->type == 'predefined' ? 'display: block;' : 'display: none;' }}">
                                            <h5>پاسخ‌ها:</h5>
                                            <button type="button" class="btn btn-secondary mb-3" onclick="addAnswer(this)">اضافه کردن پاسخ</button>
                                            @foreach($question->predefinedAnswer as $answer)
                                            <div class="mb-3">
                                                <div class="input-group">
                                                    <input type="text" name="questions[{{ $index }}][answers][]" class="form-control" value="{{ $answer->answer }}" required>
                                                    <button type="button" class="btn btn-danger" onclick="removeAnswer(this)">حذف پاسخ</button>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                        <button type="button" class="btn btn-danger" onclick="removeQuestion(this)">حذف سوال</button>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            @error('questions')
                                <div class="d-block text-danger">
                                    {{$message}}
                                </div>
                            @enderror
            <div class="form-group">
                <label for="proposal_types">نوع‌های پیشنهاد</label>
                <select class="form-control" id="proposal_types" name="proposal_types[]" multiple>
                    @foreach($proposalTypes as $type)
                        <option value="{{ $type->id }}"
                            {{ in_array($type->id, $service->proposalTypes->pluck('id')->toArray()) ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
            </div>
				@error('proposal_types')
                                <div class="d-block text-danger">
                                    {{$message}}
                                </div>
                            @enderror

                            <input type="submit" class="btn btn-primary btn-sm btn-block" value="ویرایش">

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
    let questionIndex = {{ $service->questions->count() }};

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

@endsection
