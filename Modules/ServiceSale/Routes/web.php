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
use Modules\ServiceSale\Http\Controllers\ServiceSaleController;
use Modules\Sell\Http\Controllers\InvoiceController;
   
Route::middleware(['XSS','IsInstalled'])->group(function(){
    Route::middleware(['auth','isSubscribed'])
        ->group(function(){ 
            //Sale routes
            Route::prefix('service-sale')
            ->controller(ServiceSaleController::class)
            ->name('servicesale.')
            ->group(function(){ 
                Route::get('/',                       'index')->name('index')->middleware('hasPermission:service_sale_read');  
                Route::get('/create',                 'create')->name('create')->middleware('hasPermission:service_sale_create');
                Route::post('/store',                 'store')->name('store')->middleware('hasPermission:service_sale_create');
                Route::get('/edit/{id}',              'edit')->name('edit')->middleware('hasPermission:service_sale_update'); 
                Route::put('/update',                 'update')->name('update')->middleware('hasPermission:service_sale_update');
                Route::delete('/delete/{id}',         'destroy')->name('delete')->middleware('hasPermission:service_sale_delete'); 
                Route::get('details/{id}',            'details')->name('details')->middleware('hasPermission:service_sale_read');  
                Route::get('get-all',                 'getAllSale')->name('get.all');
                
                Route::post('service/find',           'serviceFind')->name('service.find');
                Route::post('service-item',           'serviceItem')->name('service.item');
                Route::post('get-taxrate',            'getTaxrate')->name('taxrate.get');

                Route::get('manage/payment/{id}',     'managePayment')->name('manage.payment')->middleware('hasPermission:service_sale_read_payment');  
                Route::post('add/payment',            'addPayment')->name('add.payment')->middleware('hasPermission:service_sale_add_payment');  
                Route::get('edit/payment/{id}',       'editPayment')->name('edit.payment')->middleware('hasPermission:sale_update_payment');  
                Route::post('update/payment',         'updatePayment')->name('update.payment')->middleware('hasPermission:service_sale_update_payment');  
                Route::get('delete/payment/{id}',     'deletePayment')->name('delete.payment')->middleware('hasPermission:service_sale_delete_payment'); 
                 
            });
            //end Sale routes

            //invoice 
             Route::prefix('service-sale') 
                    ->name('servicesale.invoice.')
                    ->group(function(){ 
                        Route::get('/invoice/view/{id}',     [InvoiceController::class,'serviceSaleview'])->name('view');
                        Route::get('get-all-invoice',        [InvoiceController::class,'getAllServiceSaleInvoice'])->name('get.all');
                    });
            //end invoice
        });
    });
 
 