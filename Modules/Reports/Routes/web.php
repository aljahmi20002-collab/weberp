<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;
use Modules\Reports\Http\Controllers\AttendanceReportController;
use Modules\Reports\Http\Controllers\CustomerReportController;
use Modules\Reports\Http\Controllers\ExpenseReportController; 
use Modules\Reports\Http\Controllers\ProductWiseProfitController;
use Modules\Reports\Http\Controllers\ProfitLossReportController;
use Modules\Reports\Http\Controllers\PurchaseReportController;
use Modules\Reports\Http\Controllers\PurchaseReturnReportController;
use Modules\Reports\Http\Controllers\SaleReportController;
use Modules\Reports\Http\Controllers\ServiceSaleReportController;
use Modules\Reports\Http\Controllers\StockReportController;
use Modules\Reports\Http\Controllers\SupplierReportController;

Route::middleware(['XSS','IsInstalled'])->group(function(){
    Route::middleware(['auth','isSubscribed'])
        ->prefix('reports')
        ->group(function(){
            Route::prefix('attendance')
                ->controller(AttendanceReportController::class)
                ->name('reports.attendance.')
                ->group(function(){
                    Route::get('/',                      'index')->name('index')->middleware('hasPermission:attendance_reports');
                    Route::get('/reports',               'report')->name('reports')->middleware('hasPermission:attendance_reports');
                    Route::get('reports/print',          'reportPrint')->name('print'); 
                    Route::get('reports/pdf',            'reportPdfDownload')->name('download.pdf'); 
                });
            Route::prefix('profit-loss') 
                ->name('reports.profit.loss.')
                ->group(function(){
                    Route::get('/',                      [ProfitLossReportController::class, 'index'])->name('index')->middleware('hasPermission:profit_loss_reports');
                    Route::post('get-profit',            [ProfitLossReportController::class, 'getProfit'])->name('get.profit');
                    Route::post('get-purchase-profit',   [ProfitLossReportController::class, 'getPurchaseProfit'])->name('get.purchase.profit');
                });
            Route::prefix('product-wise-profit')
                ->controller(ProductWiseProfitController::class)
                ->name('reports.product.wise.profit.')
                ->group(function(){
                    Route::get('/',                    'index')->name('index')->middleware('hasPermission:product_wise_profit_reports'); 
                    Route::get('/get-profit',          'getProfit')->name('get')->middleware('hasPermission:product_wise_profit_reports'); 
             });
            
            Route::prefix('expense-report')
                ->controller(ExpenseReportController::class)
                ->name('reports.expense.report.')
                ->group(function(){
                    Route::get('/',                    'index')->name('index')->middleware('hasPermission:expense_report'); 
                    Route::get('/get-report',          'getReport')->name('get.report')->middleware('hasPermission:expense_report'); 
             });
            Route::prefix('stock-report')
                ->controller(StockReportController::class)
                ->name('reports.stock.report.')
                ->group(function(){
                    Route::get('/',                    'index')->name('index')->middleware('hasPermission:stock_report'); 
                    Route::get('/get-report',          'getReport')->name('get.report')->middleware('hasPermission:stock_report'); 
                    Route::post('/subcategory',        'subcategory')->name('subcategory'); 
             });
            Route::prefix('customer-sale-report')
                ->controller(CustomerReportController::class)
                ->name('reports.customer.sale.report.')
                ->group(function(){
                    Route::get('/',                    'index')->name('index')->middleware('hasPermission:customer_sale_report'); 
                    Route::get('/get-report',          'getReport')->name('get.report')->middleware('hasPermission:customer_sale_report'); 
            });
            //purchase report
            Route::prefix('purchase-report')
                ->controller(PurchaseReportController::class)
                ->name('reports.purchase.report.')
                ->group(function(){
                    Route::get('/',                    'index')->name('index')->middleware('hasPermission:purchase_report'); 
                    Route::get('/get-report',          'getReport')->name('get.report')->middleware('hasPermission:purchase_report'); 
            });
            //purchase return reports
            Route::prefix('purchase-return-report')
                ->controller(PurchaseReturnReportController::class)
                ->name('reports.purchase.return.report.')
                ->group(function(){
                    Route::get('/',                    'index')->name('index')->middleware('hasPermission:purchase_return_report'); 
                    Route::get('/get-report',          'getReport')->name('get.report')->middleware('hasPermission:purchase_return_report'); 
            });
            //sale reports
            Route::prefix('report-sale')
                ->controller(SaleReportController::class)
                ->name('reports.sale.report.')
                ->group(function(){
                    Route::get('/',                    'index')->name('index')->middleware('hasPermission:sale_report'); 
                    Route::get('/get-report',          'getReport')->name('get.report')->middleware('hasPermission:sale_report'); 
            });
            //supplier reports
            Route::prefix('report-supplier')
                ->controller(SupplierReportController::class)
                ->name('reports.supplier.report.')
                ->group(function(){
                    Route::get('/',                    'index')->name('index')->middleware('hasPermission:supplier_report'); 
                    Route::get('/get-report',          'getReport')->name('get.report')->middleware('hasPermission:supplier_report'); 
            });

            //service sale reports
            Route::prefix('report-service-sale')
                ->controller(ServiceSaleReportController::class)
                ->name('reports.servicesale.report.')
                ->group(function(){
                    Route::get('/',                    'index')->name('index')->middleware('hasPermission:service_sale_report'); 
                    Route::get('/get-report',          'getReport')->name('get.report')->middleware('hasPermission:service_sale_report'); 
            });

        }); 
});
