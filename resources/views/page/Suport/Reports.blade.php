@extends('layout.admin')

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

        <h2>لیست گزارش‌های تخلف متخصصین</h2>

    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>گزارش‌دهنده</th>
                <th>متخصص</th>
                <th>نوع تخلف</th>
                <th>وضعیت</th>
                <th>عملیات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reports as $report)
                <tr>
                    <td>{{ $report->id }}</td>
                    <td>{{ $report->user->name }}</td>
                    <td>{{ $report->expert->first_name  ?? 'ﻥﺎﻤﺸﺨﺻ' }} {{ $report->expert->last_name  ?? 'ﻥﺎﻤﺸﺨﺻ' }}</td>
                    <td>{{ ucfirst($report->type) }}</td>
                    <td>
                        <span class="badge bg-{{ $report->status == 'pending' ? 'warning' : ($report->status == 'reviewed' ? 'success' : 'danger') }}">
                            {{ $report->status }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.expertReports.updateStatus', [$report->id, 'reviewed']) }}" class="btn btn-success btn-sm">تایید</a>
                        <a href="{{ route('admin.expertReports.updateStatus', [$report->id, 'rejected']) }}" class="btn btn-danger btn-sm">رد</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $reports->links() }}

            </div>
        </div>
    </div>
@endsection

