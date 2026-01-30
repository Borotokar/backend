@extends('layout.admin')

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
    
    <h4>مکالمات پشتیبانی</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>شناسه</th>
                    <th>نوع</th>
                    <th>خوانده</th>
                    <th>نام</th>
                    <th>تاریخ شروع</th>
                    <th>عملیات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($conversations as $conv)
                    <tr>
                        <td>{{ $conv->id }}</td>
                        <td>
                                متخصص
                        </td>
                        <td>
                            @if ($conv->is_read)
                                <span class="badge bg-success">خوانده شده</span>
                            @else
                                <span class="badge bg-danger">جدید</span>
                            @endif
                        </td>
                        <td>
                            {{ $conv->expert?->first_name }} {{ $conv->expert?->last_name }}
                        </td>
                        <td>{{ jdate($conv->created_at)->format('Y/m/d H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.support.show', $conv->id) }}" class="btn btn-sm btn-primary">نمایش</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
	{{ $conversations->links() }}

        
    </div>
</div>
@endsection

