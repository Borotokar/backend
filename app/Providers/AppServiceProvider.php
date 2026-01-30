<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use App\Models\expert;
use App\Models\Report;
use App\Models\UserReport;
use App\Models\SupportMessages;



class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
	 Paginator::useBootstrapFour();
     View::composer('layout.admin', function ($view) {
        $accessListCount = expert::where('is_active', 0)->has('documents')->count();
        $blueTickCount = expert::where('is_blue_tick_request', 1)->count();
        $unreadCount = SupportMessages::where('sender_type', 'user')
        ->where('is_read', false)
        ->count();

        $ExpertunreadCount = SupportMessages::where('sender_type', 'expert')
        ->where('is_read', false)
        ->count();
        $reports = Report::where('status', 'pending')->count();
        $UserReports = UserReport::where('status', 'pending')->count();

        $view->with([
            'accessListCount' => $accessListCount,
            'blueTickCount' => $blueTickCount,
            'unreadCount' => $unreadCount,
            'ExpertunreadCount' => $ExpertunreadCount,
            'reports' => $reports,
            'UserReports' => $UserReports,
        ]);
     });
    }
}
