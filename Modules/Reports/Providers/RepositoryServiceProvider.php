<?php

namespace Modules\Reports\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Reports\Repositories\ProductWiseProfit\ProductWiseProfitRepository;
use Modules\Reports\Repositories\AttendanceReport\AttendanceReportInterface;
use Modules\Reports\Repositories\AttendanceReport\AttendanceReportRepository;
use Modules\Reports\Repositories\CustomerReport\CustomerReportInterface;
use Modules\Reports\Repositories\CustomerReport\CustomerReportRepository;
use Modules\Reports\Repositories\ExpenseReport\ExpenseReportInterface;
use Modules\Reports\Repositories\ExpenseReport\ExpenseReportRepository; 
use Modules\Reports\Repositories\ProductWiseProfit\ProductWiseProfitInterface;
use Modules\Reports\Repositories\ProfitLossReport\ProfitLossReportInterface;
use Modules\Reports\Repositories\ProfitLossReport\ProfitLossReportRepository;
use Modules\Reports\Repositories\PurchaseReport\PurchaseReportInterface;
use Modules\Reports\Repositories\PurchaseReport\PurchaseReportRepository;
use Modules\Reports\Repositories\PurchaseReturnReport\PurchaseReturnReportInterface;
use Modules\Reports\Repositories\PurchaseReturnReport\PurchaseReturnReportRepository;
use Modules\Reports\Repositories\SaleReport\SaleReportInterface;
use Modules\Reports\Repositories\SaleReport\SaleReportRepository;
use Modules\Reports\Repositories\StockReport\StockReportInterface;
use Modules\Reports\Repositories\StockReport\StockReportRepository;
use Modules\Reports\Repositories\SupplierReport\SupplierReportInterface;
use Modules\Reports\Repositories\SupplierReport\SupplierReportRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(AttendanceReportInterface::class,                          AttendanceReportRepository::class);
        $this->app->bind(ProfitLossReportInterface::class,                          ProfitLossReportRepository::class);
        $this->app->bind(ProductWiseProfitInterface::class,                         ProductWiseProfitRepository::class); 
        $this->app->bind(ExpenseReportInterface::class,                             ExpenseReportRepository::class);
        $this->app->bind(StockReportInterface::class,                               StockReportRepository::class);
        $this->app->bind(CustomerReportInterface::class,                            CustomerReportRepository::class);
        $this->app->bind(PurchaseReportInterface::class,                            PurchaseReportRepository::class);
        $this->app->bind(PurchaseReturnReportInterface::class,                      PurchaseReturnReportRepository::class);
        $this->app->bind(SaleReportInterface::class,                                SaleReportRepository::class);
        $this->app->bind(SupplierReportInterface::class,                            SupplierReportRepository::class);

    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
