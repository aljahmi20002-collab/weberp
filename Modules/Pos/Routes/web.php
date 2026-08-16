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
use Modules\Pos\Http\Controllers\CustomerPosReportContollerController;
use Modules\Pos\Http\Controllers\PosController;
use Modules\Pos\Http\Controllers\PosReportController;
use Modules\Pos\Http\Controllers\ProductWisePosProfitController;
use Modules\Sell\Http\Controllers\InvoiceController;

Route::middleware(['XSS','IsInstalled'])->group(function(){
    Route::middleware(['auth','isSubscribed'])
        ->group(function(){
        //pos routes
            Route::prefix('pos')
            ->controller(PosController::class)
            ->name('pos.')
            ->group(function(){ 
                Route::get('/',                        'index')->name('index')->middleware('hasPermission:pos_read');  
                Route::get('/print/{id}',              'print')->name('print')->middleware('hasPermission:pos_read');  
                Route::get('/products',                'products')->name('products');
                Route::get('/filter-products',         'FilterProduct')->name('filter.products');
                Route::post('/store',                   'store')->name('store')->middleware('hasPermission:pos_create'); 
                Route::get('list',                      'list')->name('list')->middleware('hasPermission:pos_read'); 
                Route::get('/edit/{id}',                'edit')->name('edit')->middleware('hasPermission:pos_update'); 
                Route::put('/update',                 'update')->name('update')->middleware('hasPermission:pos_update');
                Route::delete('/delete/{id}',           'destroy')->name('delete')->middleware('hasPermission:pos_delete'); 
                Route::get('details/{id}',              'details')->name('details')->middleware('hasPermission:pos_read');  

                Route::post('variation/location/find',   'VariationLocationFind')->name('variation.location.find');
                Route::post('variation-location-item',   'VariationLocationItem')->name('variation.location.item');
                Route::post('variation-location-item-get', 'VariationLocationItemGet')->name('variation.location.item.get');
                Route::post('get-taxrate',               'getTaxrate')->name('taxrate.get');

                Route::get('manage/payment/{id}',        'managePayment')->name('manage.payment')->middleware('hasPermission:pos_read_payment');  
                Route::post('add/payment',               'addPayment')->name('add.payment')->middleware('hasPermission:pos_add_payment');  
                Route::get('edit/payment/{id}',          'editPayment')->name('edit.payment')->middleware('hasPermission:pos_update_payment');  
                Route::post('update/payment',            'updatePayment')->name('update.payment')->middleware('hasPermission:pos_update_payment');  
                Route::get('delete/payment/{id}',        'deletePayment')->name('delete.payment')->middleware('hasPermission:pos_delete_payment'); 
                Route::get('invoice/view/{id}',           [InvoiceController::class,'posInvoiceView'])->name('invoice.view'); 
                Route::get('invoice/get-all-pos',         [InvoiceController::class,'getAllPosInvoice'])->name('invoice.get.all'); 
                Route::get('get-all-pos',                 'getAllPos')->name('get.all.pos');
            });
        //end pos routes

        //reports
        Route::prefix('reports')->group(function(){
            //product wise pos report
            Route::prefix('product-wise-pos-profit')
                ->controller(ProductWisePosProfitController::class)
                ->name('reports.product.wise.pos.profit.')
                ->group(function(){
                    Route::get('/',                    'index')->name('index')->middleware('hasPermission:product_wise_pos_profit_reports'); 
                    Route::get('/get-profit',          'getProfit')->name('get')->middleware('hasPermission:product_wise_pos_profit_reports'); 
            });
            //customer pos report
            Route::prefix('customer-pos-report')
            ->controller(CustomerPosReportContollerController::class)
            ->name('reports.customer.pos.report.')
            ->group(function(){
                Route::get('/',                    'index')->name('index')->middleware('hasPermission:customer_pos_report'); 
                Route::get('/get-report',          'getReport')->name('get.report')->middleware('hasPermission:customer_pos_report'); 
            });

            //pos reports
            Route::prefix('report-pos')
                ->controller(PosReportController::class)
                ->name('reports.pos.report.')
                ->group(function(){
                    Route::get('/',                    'index')->name('index')->middleware('hasPermission:sale_report'); 
                    Route::get('/get-report',          'getReport')->name('get.report')->middleware('hasPermission:sale_report'); 
            });
             
        });

    });
    Route::get('pos/mobile/print/{id}',              [PosController::class,'mobilePrint']);  
});
 

