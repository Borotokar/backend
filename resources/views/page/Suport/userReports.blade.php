@extends('layout.admin')
@section('title')
    لیست تخلفات کاربران بروتوکار
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
                






       <h2 class="mb-4">گزارش تخلفات کاربران</h2>

<table class="table table-striped">
    <thead class="table">
        <tr>
            <th>#</th>
            <th>گزارش‌دهنده (متخصص)</th>
            <th>کاربر گزارش‌شده</th>
            <th>نوع تخلف</th>
            <th>توضیحات</th>
            <th>وضعیت</th>
            <th>عملیات</th>
        </tr>
    </thead>
    <tbody>
        @foreach($reports as $report)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $report->expert->first_name  ?? 'نامشخص' }} {{ $report->expert->last_name  ?? 'ﻥﺎﻤﺸﺨﺻ' }}</td>
            <td>{{ $report->user->name ?? 'نامشخص' }}</td>
            <td>
                @switch($report->violation_type)
                    @case('chat') تخلف در چت @break
                    @case('profile') تخلف در پروفایل @break
                    @case('order') تخلف در سفارش @break
                @endswitch
            </td>
            <td>{{ $report->description }}</td>
            <td>
                <span class="badge 
                    {{ $report->status == 'pending' ? 'bg-warning' : ($report->status == 'reviewed' ? 'bg-success' : 'bg-danger') }}">
                    {{ $report->status == 'pending' ? 'در انتظار بررسی' : ($report->status == 'reviewed' ? 'بررسی شده' : 'رد شده') }}
                </span>
            </td>
            <td>
                <form action="{{ route('admin.user-reports.update', $report->id) }}" method="post">
                    @csrf
                    @method('PATCH')
                    <select name="status" class="form-select d-inline-block w-auto">
                        <option value="pending" {{ $report->status == 'pending' ? 'selected' : '' }}>در انتظار بررسی</option>
                        <option value="reviewed" {{ $report->status == 'reviewed' ? 'selected' : '' }}>بررسی شده</option>
                        <option value="rejected" {{ $report->status == 'rejected' ? 'selected' : '' }}>رد شده</option>
                    </select>
                    <button type="submit" class="btn btn-primary btn-sm">بروزرسانی</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>


            </div>
        </div>
    </div>
{{-- </div> --}}

@endsection

