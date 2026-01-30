@extends('layout.admin')
@section('title')
    پنل مدیریت
@endsection
@section('body')
<div class="container-fluid ">

    <!-- content -->
     @if($ExpertunreadCount != 0)
        <div class="alert alert-second alert-shade alert-dismissible " role="alert">
                پشتیبانی متخصص {{$ExpertunreadCount}} پیام خوانده نشده !
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
        </div>
    @endif
     @if($unreadCount != 0)

        <div class="alert alert-second alert-shade alert-dismissible " role="alert">
                پشتیبانی کاربران {{$unreadCount}} پیام خوانده نشده !
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
        </div>
    @endif
    <!-- breadcrumb -->

    <div class=" m-1 pb-4 mb-3 ">
        <div class="col-xs-12  col-sm-12  col-md-12  col-lg-12 p-2">
            <div class="page-header breadcrumb-header ">
                <div class="row align-items-end ">
                    <div class="col-lg-8">
                        <div class="page-header-title text-left-rtl">
                            <div class="d-inline">
                                <h3 class="lite-text ">داشبورد</h3>
                                <span class="lite-text ">گزارش ها و آمار</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item "><a href="{{route('admin.home')}}"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item active">داشبورد</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- alert -->
    <!-- <div class="row m-1 pb-3 ">

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-2">
            <div class="alert alert-danger alert-shade alert-dismissible fade show" role="alert">
                <strong>Danger!</strong> Your Disk is Low.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        </div>

    </div> -->
    <!-- widget -->
    <div class="row m-1 mb-2">
        <div class="col-xl-3 col-md-6 col-sm-6 p-2">
            <div class="box-card text-right mini animate__animated animate__flipInY   "><i
                    class="fab far fa-chart-bar b-first" aria-hidden="true"></i>
                <span class="mb-1 c-first">تعداد کاربران</span>
                <span>{{ $users }}</span>
                <p class="mt-3 mb-1 text-right"><i class="far fas fa-wallet mr-1 c-first"></i> در حال
                    پیشرفت</p>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 col-sm-6 p-2">
            <div class="box-card text-right mini animate__animated animate__flipInY    "><i
                    class="fab far fa-clock b-second" aria-hidden="true"></i>
                <span class="mb-1 c-second">کاربران امروز</span>
                <span>{{$todayUsers}}</span>
                <p class="mt-3 mb-1 text-right"><i class="far fas fa-wifi mr-1 c-second"></i>در حال پیشرفت
                </p>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 col-sm-6 p-2">
            <div class="box-card text-right mini animate__animated animate__flipInY   "><i
                    class="fab far fa-comments b-third" aria-hidden="true"></i>
                <span class="mb-1 c-third">تعداد متخصصان</span>
                <span>{{ $experts }}</span>
                <p class="mt-3 mb-1 text-right"><i class="fab fa-whatsapp mr-1 c-third"></i>در حال پیشرفت
                </p>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 col-sm-6 p-2">
            <div class="box-card text-right mini animate__animated animate__flipInY   "><i
                    class="fab far fa-gem b-forth" aria-hidden="true"></i>
                <span class="mb-1 c-forth">درآمد این ماه</span>
                <span>{{number_format($totalCommission)}}</span>
                <p class="mt-3 mb-1 text-right"><i class="fab fa-bluetooth mr-1 c-forth"></i>در حال پیشرفت
                </p>
            </div>
        </div>
    </div>

    <br>

    <div class="row m-1 mb-2">
        <div class="col-xl-3 col-md-6 col-sm-6 p-2">
            <div class="box-card text-right mini animate__animated animate__flipInY   "><i
                    class="fab far fa-chart-bar b-first" aria-hidden="true"></i>
                <span class="mb-1 c-first">سفارشات امروز</span>
                <span>{{ count($orders) }}</span>
                <p class="mt-3 mb-1 text-right"><i class="far fas fa-wallet mr-1 c-first"></i> در حال
                    پیشرفت</p>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 col-sm-6 p-2">
            <div class="box-card text-right mini animate__animated animate__flipInY    "><i
                    class="fab far fa-clock b-second" aria-hidden="true"></i>
                <span class="mb-1 c-second">متخصصین جدید </span>
                <span>{{$todayExpert}}</span>
                <p class="mt-3 mb-1 text-right"><i class="far fas fa-wifi mr-1 c-second"></i>در حال پیشرفت
                </p>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 col-sm-6 p-2">
            <div class="box-card text-right mini animate__animated animate__flipInY   "><i
                    class="fab far fa-comments b-third" aria-hidden="true"></i>
                <span class="mb-1 c-third">نظرات امروز</span>
                <span>{{count($reviews)}}</span>
                <p class="mt-3 mb-1 text-right"><i class="fab fa-whatsapp mr-1 c-third"></i>در حال پیشرفت
                </p>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 col-sm-6 p-2">
            <div class="box-card text-right mini animate__animated animate__flipInY   "><i
                    class="fab far fa-gem b-forth" aria-hidden="true"></i>
                <span class="mb-1 c-forth">واریزی این ماه</span>
                <span>{{number_format($totalDespolist)}}</span>
                <p class="mt-3 mb-1 text-right"><i class="fab fa-bluetooth mr-1 c-forth"></i>در حال پیشرفت
                </p>
            </div>
        </div>
    </div>
</div>
    </div>


<div class="container mt-5">
    <h2 class="text-center mb-4">لیست نوتیفیکیشن‌ها</h2>

    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">نوتیفیکیشن‌ها</h5>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>نوع</th>
                        <th>مدل</th>
                        <th>پیام</th>
                        <th>تاریخ</th>
                        <th>وضعیت</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($notifications as $notification)
                        <tr class="{{ $notification->read_at ? '' : 'table-warning' }}">
                            <td>{{ $notification->id }}</td>
                            <td>{{ ucfirst($notification->type) }}</td>
                            <td>{{ class_basename($notification->model) }}</td>
                            <td>{{ $notification->message }}</td>
                            <td>{{ $notification->created_at->format('Y/m/d H:i') }}</td>
                            <td>
                                @if ($notification->read_at)
                                    <span class="badge bg-success">خوانده شده</span>
                                @else
                                    <span class="badge bg-danger">خوانده نشده</span>
                                @endif
                            </td>
                            <td>
                                @if (!$notification->read_at)
                                    <form action="{{ route('admin.notifications.markAsRead', $notification->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">علامت‌گذاری خوانده‌شده</button>
                                    </form>
                                @else
                                    <span class="text-muted">هیچ</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center p-4">هیچ نوتیفیکیشنی وجود ندارد.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
