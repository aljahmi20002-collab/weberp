<?php

namespace Modules\Pos\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Modules\Pos\Http\ViewComposer\BranchDashboard\ThirtyDaysPosChartComposer as branchThirtyDaysPosChartComposer;
use Modules\Pos\Http\ViewComposer\BusinessDashboard\ThirtyDaysPosChartComposer;
use Modules\Pos\Repositories\CustomerPosReport\CustomerPosReportInterface;
use Modules\Pos\Repositories\CustomerPosReport\CustomerPosReportRepository;
use Modules\Pos\Repositories\PosInterface;
use Modules\Pos\Repositories\PosReport\PosReportInterface;
use Modules\Pos\Repositories\PosReport\PosReportRepository;
use Modules\Pos\Repositories\PosRepository;
use Modules\Pos\Repositories\ProductWisePosProfit\ProductWisePosProfitInterface;
use Modules\Pos\Repositories\ProductWisePosProfit\ProductWisePosProfitRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(PosInterface::class,      PosRepository::class);
        $this->app->bind(ProductWisePosProfitInterface::class,      ProductWisePosProfitRepository::class);
        $this->app->bind(CustomerPosReportInterface::class,         CustomerPosReportRepository::class);
        $this->app->bind(PosReportInterface::class,                 PosReportRepository::class);

    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
   
    public function boot(){
        
        View::composer('pos::business_dashboard.thirty_days_pos_chart',  ThirtyDaysPosChartComposer::class);
        View::composer('pos::branch_dashboard.thirty_days_pos_chart',     branchThirtyDaysPosChartComposer::class);
    }
}
