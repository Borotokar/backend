@extends('layout.admin')
@section('title')
    لیست سفارشات بروتوکار
@endsection

@section('body')
<div class="container-fluid py-4">

    {{-- پیام موفقیت/خطا --}}
    @if (Session::get('success'))				
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            {{ Session::get('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">×</button>
        </div>
    @endif

    @if (Session::get('fail'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            {{ Session::get('fail') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">×</button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">

            {{-- سرچ --}}
            <form action="{{ route('admin.order.search') }}" method="GET" class="mb-4">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" 
                           placeholder="جست‌وجوی سفارشات" value="{{ request('search') }}">
                    <button class="btn btn-primary" type="submit">جست‌وجو</button>
                </div>
            </form>         

            {{-- هدر کارت --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title fw-bold mb-0">لیست سفارشات بروتوکار</h5>
                <a href="#addService" class="btn btn-success btn-sm">
                    + افزودن سفارش
                </a>
            </div>

            <hr>

            {{-- جدول سفارشات --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">ردیف</th>
                            <th scope="col">کد پیگیری</th>
                            <th scope="col">تصویر خدمت</th>
                            <th scope="col">نام کاربر</th>
                            <th scope="col">نام خدمت</th>
                            <th scope="col">توضیحات</th>
                            <th scope="col">ظرفیت باقی‌مانده</th>
                            <th scope="col">تاریخ و ساعت انجام</th>
                            <th scope="col">تاریخ ثبت</th>
                            <th scope="col">عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->id }}</td>
                                <td>
                                    <img src="{{ URL::asset($item->service()->first()->image) }}" 
                                         alt="service image" class="rounded" height="50" width="50">
                                </td>
                                <td>{{ $item->user()->first()->name }}</td>
                                <td>{{ $item->service()->first()->title }}</td>
                                <td>{{ Str::limit($item->description, 20, '...') }}</td>
                                <td>{{ 10 - count($item->bids) }}</td>
                                <td>
                                    {{ $item->completion_date }}<br>
                                    <small class="text-muted">{{ Str::limit($item->completion_time, 5, "") }}</small>
                                </td>
                                <td>{{ \Morilog\Jalali\Jalalian::forge($item->created_at)->format('Y-m-d') }}</td>
                                <td>
                                    <div class="d-flex flex-column gap-2">
                                        <a href="{{ route('admin.order', ['id'=>$item->id]) }}" 
                                           class="btn btn-outline-primary btn-sm mb-1">
                                            نمایش
                                        </a>
                                        <form action="{{ route('admin.deleteorder', ['id'=>$item->id]) }}" method="post">
                                            @csrf
                                            <button type="submit" 
                                                    class="btn btn-outline-danger btn-sm">
                                                حذف
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- صفحه‌بندی --}}
            <div class="d-flex justify-content-center mt-3">
                {{ $orders->links() }}
            </div>

        </div>
    </div>
</div>
@endsection
