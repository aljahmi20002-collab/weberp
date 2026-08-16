<?php

namespace App\Providers;
 
use App\Http\ViewComposer\BusinessDashboard\ThirtyDaysSaleChartComposer;
use App\Http\ViewComposer\BranchDashboard\ThirtyDaysSaleChartComposer as BranchThirtyDaysSaleChartComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('backend.business_dashboard.thirty_days_sales_chart',ThirtyDaysSaleChartComposer::class);
        View::composer('backend.branch_dashboard.thirty_days_sales_chart',BranchThirtyDaysSaleChartComposer::class);
    }
}
