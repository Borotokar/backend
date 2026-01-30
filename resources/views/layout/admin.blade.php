
<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<title>@yield('title')</title>
	<meta name="description" content="nozha admin panel fully support rtl with complete dark mode css to use. ">
	<meta name=”robots” content="index, follow">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="apple-touch-icon" sizes="180x180" href="/img/favicon/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/img/favicon/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/img/favicon/favicon-16x16.png">
	<link rel="manifest" href="/img/favicon/site.webmanifest">
	<link rel="mask-icon" href="/img/favicon/safari-pinned-tab.svg" color="#5bbad5">
	<meta name="msapplication-TileColor" content="#2b5797">
	<meta name="theme-color" content="#ffffff">
    <!-- Place favicon.ico in the root directory -->
    <link rel="stylesheet" href="/css/normalize.css">
    <link href="/css/fontawsome/all.min.css" rel="stylesheet">
    <link rel="stylesheet"
        href="https://unpkg.com/bootstrap-material-design@4.1.1/dist/css/bootstrap-material-design.min.css"
        integrity="sha384-wXznGJNEXNG1NFsbm0ugrLFMQPWswR3lds2VeinahP8N0zJw9VWSopbjv2x7WCvX" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
        integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="/css/jalalidatepicker.min.css">

    <link rel="stylesheet" href="/css/main.css">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>

<body class="rtl persianumber">

    <div class="bmd-layout-container bmd-drawer-f-l avam-container animated bmd-drawer-in">
        <header class="bmd-layout-header ">
            <div class="navbar navbar-light bg-faded animate__animated animate__fadeInDown">
                <button class="navbar-toggler animate__animated animate__wobble animate__delay-2s" type="button"
                    data-toggle="drawer" data-target="#dw-s1">
                    <span class="navbar-toggler-icon"></span>
                    <!-- <i class="material-Animation">menu</i> -->
                </button>
                <ul class="nav navbar-nav p-0">
                    
                    {{-- <li class="nav-item">
                        <div class="dropdown">
                            <button class="btn  dropdown-toggle m-0" type="button" id="dropdownMenu3"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="far fa-bell  fa-lg "></i><span
                                    class="badge badge-pill badge-warning animate__animated animate__flash animate__repeat-3 animate__slower animate__delay-2s">5</span>
                            </button>
                            <div aria-labelledby="dropdownMenu2"
                                class="dropdown-menu dropdown-menu-right dropdown-menu dropdown-menu-right-lg">
                                <span class="dropdown-item dropdown-header persianumber">15 اطلاعیه</span>
                                <div class="dropdown-divider"></div>
                                <a href="#" class="dropdown-item">
                                    <i class="far fa-envelope c-main mr-2"></i> 4 پیام جدید
                                    <span class="float-right-rtl text-muted text-sm">3 دقیقه پیش</span>
                                </a>
                                <div class="dropdown-divider"></div>
                                <a href="#" class="dropdown-item">
                                    <i class="far fa-user c-main mr-2"></i> 8 درخواست دوستی
                                    <span class="float-right-rtl text-muted text-sm">12 ساعت پیش</span>
                                </a>
                                <div class="dropdown-divider"></div>
                                <a href="#" class="dropdown-item">
                                    <i class="far fa-file c-main mr-2"></i> 3 گزارش جدید
                                    <span class="float-right-rtl text-muted text-sm">2 روز پیش</span>
                                </a>
                                <div class="dropdown-divider"></div>
                                <a href="#" class="dropdown-item dropdown-footer">دیدن همه</a>
                            </div>
                        </div>
                    </li> --}}

                    <li class="nav-item">
                        <div id="responseContainer">{{ Morilog\Jalali\Jalalian::now()->addHours(3)->addMinutes(30)->format('%H:%M %A %d %B %Y') }}</div>
                    </li>
                    <li class="nav-item"> <img src="{{URL::asset(Auth::user()->picture)}}" alt="..."
                            class="rounded-circle screen-user-profile"></li>
                    <li class="nav-item">
                        <div class="dropdown">
                            <button class="btn  dropdown-toggle m-0" type="button" id="dropdownMenu4"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{Auth::user()->name}}
                            </button>
                            <div aria-labelledby="dropdownMenu4"
                                class="dropdown-menu  pl-3 dropdown-menu-right dropdown-menu dropdown-menu-right"
                                aria-labelledby="dropdownMenu2">
                                <a href="{{ route('admin.editprofile') }}">
                                    <button class="dropdown-item" type="button"><i
                                        class="far fa-user c-main fa-sm mr-2"></i>پروفایل</button>
                                </a>
                                <button onclick="dark()" class="dropdown-item" type="button"><i
                                        class="fas fa-moon fa-sm c-main mr-2"></i>حالت شب</button>
                                <button class="dropdown-item" type="button"><i
                                        class="fas fa-cog c-main fa-sm mr-2"></i>تنظیمات</button>
							
								<a href="{{ route('admin.logout_handller') }}"><button class="dropdown-item" type="submit" ><i
                                        class="fas fa-sign-out-alt c-main fa-sm mr-2" ></i>خروج</button>
								</a>
                            </div>
                        </div>
                    </li>




                </ul>
            </div>
        </header>
        <div id="dw-s1" class="bmd-layout-drawer bg-faded ">
 
            <div class="container-fluid side-bar-container ">
                <header class="pb-0" style="align-content: center;">
                    <a class="navbar-brand ">
                        <img src="/img/favicon/android-chrome-512x512.png" alt="..." class="mw-center " height="60" width="60" style="align-items: center;  border-radius: 10px">
                        </object>
                    </a>
                </header>
                <br>
                <ul class="side a-collapse  ">
                    <a class="ul-text  fnt-mxs"><i class="fas fa-tachometer-alt mr-1"></i> داشبورد
                        <i class="fas fa-chevron-down arrow"></i></a>
                    <div class="side-item-container animated">
                        
                        <li class="side-item active selected"><a href="{{route('admin.home')}}" class="fnt-mxs"><i class="fas fa-angle-right mr-2"></i> داشبورد</a></li>
                    </div>
                </ul>


		@if (Auth::user()->hasRole("general_manager"))
                <p class="side-comment  fnt-mxs">ادمین</p>
                <li class="side a-collapse short ">
                    <a href="{{ route('admin.admins') }}" class="side-item  fnt-mxs "><ion-icon name="mail-unread-outline" style="font-size: 14x;"></ion-icon>مدیریت ادمین ها</a>
                </li>
            	@endif
            <br>
        
        
        <ul class="side a-collapse  ">
		<a class="ul-text  fnt-mxs"><i class="fas fa-tachometer-alt mr-1"></i> خدمات
        <!-- <span class="badge badge-info">4</span> -->
        <i class="fas fa-chevron-down arrow"></i></a>
        <div class="side-item-container animated">
		<!-- <p class="side-comment  fnt-mxs">خدمات</p> -->
                @if (Auth::user()->hasRole("Service_manager") || Auth::user()->hasRole("general_manager"))
		<li class="side a-collapse short " style="font-size: 14px">
                    <a href="{{route('admin.services')}}" class="side-item "><ion-icon style="font-size: 14px;" name="list-outline"></ion-icon>  خدمات</a>
                </li>
                @endif
            @if (Auth::user()->hasRole("categoris_manager") || Auth::user()->hasRole("general_manager"))

		<li class="side a-collapse short " style="font-size: 14px">
                    <a href="{{route('admin.categoris')}}" class="side-item "><ion-icon style="font-size: 14px;" name="list-outline"></ion-icon>  دسته‌بندی  </a>
                </li>
		@endif
            @if (Auth::user()->hasRole("type_manager") || Auth::user()->hasRole("general_manager"))

                <li class="side a-collapse short " style="font-size: 14px">
                    <a href="{{route('admin.types')}}" class="side-item "><ion-icon style="font-size: 14px;" name="list-outline"></ion-icon>  نوع </a>
                </li>
	@endif
            @if (Auth::user()->hasRole("propesal_type_manager") || Auth::user()->hasRole("general_manager"))

		<li class="side a-collapse short " style="font-size: 14px">
                    <a href="{{route('admin.proposal_types.index')}}" class="side-item "><ion-icon style="font-size: 14px;" name="list-outline"></ion-icon>نوع پیشنهاد</a>
                </li>	
	@endif
            </div>
                    </ul>
            <br>

        <ul class="side a-collapse  ">
		<a class="ul-text  fnt-mxs"><i class="fas fa-tachometer-alt mr-1"></i> پشتیبانی
        <!-- <span class="badge badge-info">4</span> -->
        <i class="fas fa-chevron-down arrow"></i></a>
        <div class="side-item-container animated">
                <!-- <p class="side-comment  fnt-mxs">پشتیبانی</p> -->
            @if (Auth::user()->hasRole("Orders_manager") || Auth::user()->hasRole("general_manager"))

                <li class="side a-collapse short ">
                    <a href="{{ route('admin.orders') }}" class="side-item  fnt-mxs "><ion-icon name="mail-unread-outline" style="font-size: 14x;"></ion-icon> سفارش ها</a>
                </li>
	@endif
            @if (Auth::user()->hasRole("transactions_manager") || Auth::user()->hasRole("general_manager"))

                <li class="side a-collapse short ">
                    <a href="{{ route('admin.transactions') }}" class="side-item  fnt-mxs "><ion-icon name="mail-unread-outline" style="font-size: 14x;"></ion-icon> تراکنش ها</a>
                </li>
	   @endif
         
	       <li class="side a-collapse short ">
                <span class="badge badge-info">{{$UserReports}}</span>
                <a href="{{ route('admin.user-reports') }}" class="side-item  fnt-mxs "><ion-icon name="mail-unread-outline" style="font-size: 14x;"></ion-icon> گزارشات تخلف کاربران</a>
            </li> 

            <li class="side a-collapse short ">
                <span class="badge badge-info">{{$reports}}</span>
                <a href="{{ route('admin.expertReports') }}" class="side-item  fnt-mxs "><ion-icon name="mail-unread-outline" style="font-size: 14x;"></ion-icon> گزارشات تخلف متخصصین</a>
            </li>  

                <li class="side a-collapse short ">
                    <a href="{{ route('admin.chats.index') }}" class="side-item  fnt-mxs "><ion-icon name="mail-unread-outline" style="font-size: 14x;"></ion-icon>چت ها</a>
                </li>

                <li class="side a-collapse short ">
                <span class="badge badge-info">{{$unreadCount}}</span>

                    <a href="{{ route('admin.support.uindex') }}" class="side-item  fnt-mxs "><ion-icon name="mail-unread-outline" style="font-size: 14x;"></ion-icon>پشتیبانی کاربران</a>
                </li>

                <li class="side a-collapse short ">
                <span class="badge badge-info">{{$ExpertunreadCount}}</span>

                    <a href="{{ route('admin.support.eindex') }}" class="side-item  fnt-mxs "><ion-icon name="mail-unread-outline" style="font-size: 14x;"></ion-icon>پشتیبانی متخصصین</a>
                </li>

                    </div>
                    </ul>

                    <br>
            
        <ul class="side a-collapse  ">
		<a class="ul-text  fnt-mxs"><i class="fas fa-tachometer-alt mr-1"></i> اپ کاربر
        <!-- <span class="badge badge-info">4</span> -->
        <i class="fas fa-chevron-down arrow"></i></a>
        <div class="side-item-container animated">
                <!-- <p class="side-comment  fnt-mxs">اپ کاربر</p> -->
            @if (Auth::user()->hasRole("User_manager") || Auth::user()->hasRole("general_manager"))
        
        <li class="side a-collapse short ">
                    <a href="{{ route('admin.users') }}" class="side-item  fnt-mxs "><ion-icon name="mail-unread-outline" style="font-size: 14x;"></ion-icon> لیست کاربران</a>
                </li>
	    @endif
            @if (Auth::user()->hasRole("Notif_manager") || Auth::user()->hasRole("general_manager"))

                <li class="side a-collapse short ">
                    <a href="{{ route('admin.notif')  }}" class="side-item  fnt-mxs "><ion-icon name="mail-unread-outline" style="font-size: 14x;"></ion-icon> ارسال نوتیفیکیشن</a>
                </li>
		@endif
	     <li class="side a-collapse short ">
                    <a href="{{ route('admin.msg')  }}" class="side-item  fnt-mxs "><ion-icon name="mail-unread-outline" style="font-size: 14x;"></ion-icon>ارسال پیام به کاربر</a>
                </li>
            @if (Auth::user()->hasRole("user_app_setting_manager") || Auth::user()->hasRole("general_manager"))

                <li class="side a-collapse short ">
                    <a href="{{ route('admin.userappsetting') }}" class="side-item  fnt-mxs "><ion-icon name="mail-unread-outline" style="font-size: 14x;"></ion-icon> تنظیمات اپ کاربر </a>
                </li>
	   @endif    
                    </div>
                    </ul>           

                    <br>

        <ul class="side a-collapse  ">
		<a class="ul-text  fnt-mxs"><i class="fas fa-tachometer-alt mr-1"></i> اپ متخصص
        <!-- <span class="badge badge-info">4</span> -->
        <i class="fas fa-chevron-down arrow"></i></a>
        <div class="side-item-container animated">
                <!-- <p class="side-comment  fnt-mxs">اپ متخصص</p> -->
            @if (Auth::user()->hasRole("expert_allow_list_manager") || Auth::user()->hasRole("general_manager"))
		<li class="side a-collapse short ">
        <span class="badge badge-info">{{$accessListCount}}</span>

                    <a href="{{ route('admin.expertsaccesslist') }}" class="side-item  fnt-mxs "><ion-icon name="mail-unread-outline" style="font-size: 14x;"></ion-icon> صف تایید متخصصین</a>
                </li>
	   @endif

	            <li class="side a-collapse short ">
                <a href="{{ route('admin.expertRejectList') }}" class="side-item  fnt-mxs "><ion-icon name="mail-unread-outline" style="font-size: 14x;"></ion-icon> لیست متخصصین ردشده</a>
            </li> 
            @if (Auth::user()->hasRole("Expert_manager") || Auth::user()->hasRole("general_manager"))

                <li class="side a-collapse short ">
                    <a href="{{ route('admin.experts') }}" class="side-item  fnt-mxs "><ion-icon name="mail-unread-outline" style="font-size: 14x;"></ion-icon> لیست متخصصین</a>
                </li>
	   @endif

        @if (Auth::user()->hasRole("Expert_manager") || Auth::user()->hasRole("general_manager"))

                <li class="side a-collapse short ">
                <span class="badge badge-info">{{$blueTickCount}}</span>

                    <a href="{{ route('admin.expertsBlue') }}" class="side-item  fnt-mxs "><ion-icon name="mail-unread-outline" style="font-size: 14x;"></ion-icon> صف انتظار تیک آبی</a>
                </li>
	   @endif
            @if (Auth::user()->hasRole("wallet_manager")|| Auth::user()->hasRole("general_manager"))               
     
	<li class="side a-collapse short ">
                    <a href="{{ route('admin.wallets') }}" class="side-item  fnt-mxs "><ion-icon name="mail-unread-outline" style="font-size: 14x;"></ion-icon>لیست کیف پول</a>
         </li>
	 @endif
	 <li class="side a-collapse short ">
                    <a href="{{ route('admin.emsg')  }}" class="side-item  fnt-mxs "><ion-icon name="mail-unread-outline" style="font-size: 14x;"></ion-icon>ارسال پیام به متخصص</a>
    </li>
	 <li class="side a-collapse short ">
                    <a href="{{ route('admin.enotif')  }}" class="side-item  fnt-mxs "><ion-icon name="mail-unread-outline" style="font-size: 14x;"></ion-icon>ارسال نوتیفیکیشن به متخصص</a>
    </li>
        @if (Auth::user()->hasRole("expert_app_manager") || Auth::user()->hasRole("general_manager"))
	<li class="side a-collapse short ">
	    <a href="{{route('admin.expertappsetting')}}" class="side-item  fnt-mxs "><ion-icon name="mail-unread-outline" style="font-size: 14x;"></ion-icon> تنظیمات اپ متخصص </a>
	</li>
	@endif
            @if (Auth::user()->hasRole("comments_manager") || Auth::user()->hasRole("general_manager"))
        
        <li class="side a-collapse short ">
                    <a href="{{ route('admin.reviews') }}" class="side-item  fnt-mxs "><ion-icon name="mail-unread-outline" style="font-size: 14x;"></ion-icon> تایید نظرات </a>
                </li>
	   @endif
                {{-- <li class="side a-collapse short ">
                    <a href="#" class="side-item  fnt-mxs "><ion-icon name="mail-unread-outline" style="font-size: 14x;"></ion-icon> </a>
                </li> --}}
            </div>
                </div>
                </ul>
                <br>

        </div>
        <main class="bmd-layout-content">
           @yield('body')
        </main>
    </div>

    </div>

    <script src="/js/vendor/modernizr.js"></script>
    <!-- <script src="https://code.jquery.com/jquery-3.2.1.min.js"
        integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>

    <script>
        window.jQuery || document.write('<script src="js/vendor/jquery-3.2.1.min.js"><\/script>')
    </script>
    <script src="https://unpkg.com/popper.js@1.12.6/dist/umd/popper.js"
        integrity="sha384-fA23ZRQ3G/J53mElWqVJEGJzU0sTs+SvzG8fXVWP+kJQ1lwFAOkcUOysnlKJC33U" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://unpkg.com/bootstrap-material-design@4.1.1/dist/js/bootstrap-material-design.js"
        integrity="sha384-CauSuKpEqAFajSpkdjv3z9t8E7RlpJ1UP0lKM/+NdtSarroVKu069AlsRPKkFBz9" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
    <script src="/js/persianumber.min.js"></script>

        <!-- درون ویوی blade خود -->
    <script src="/js/jalalidatepicker.min.js"></script>

    <script>
        $(document).ready(function () {
            $('body').bootstrapMaterialDesign();
            $('.persianumber').persiaNumber();

        });
    </script>
    <script>
        ! function (d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (!d.getElementById(id)) {
                js = d.createElement(s);
                js.id = id;
                js.src = 'https://weatherwidget.io/js/widget.min.js';
                fjs.parentNode.insertBefore(js, fjs);
            }
        }(document, 'script', 'weatherwidget-io-js');
    </script>
    <script src="/js/main.js"></script>
    
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script type="text/javascript" src="js/jalalidatepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    @yield('script')
   




</body>

</html>
