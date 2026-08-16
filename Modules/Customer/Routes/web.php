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
use Modules\Customer\Http\Controllers\CustomerController;

Route::middleware(['XSS','IsInstalled'])->group(function(){
    Route::middleware(['auth','isSubscribed'])
        ->prefix('customers')
        ->controller(CustomerController::class)
        ->name('customers.')
        ->group(function(){
            Route::get('/',                      'index')->name('index')->middleware('hasPermission:customer_read'); 
            Route::get('/create',                'create')->name('create')->middleware('hasPermission:customer_create');
            Route::post('/store',                'store')->name('store')->middleware('hasPermission:customer_create');
            Route::get('/edit/{id}',             'edit')->name('edit')->middleware('hasPermission:customer_update');
            Route::put('/update',                'update')->name('update')->middleware('hasPermission:customer_update');
            Route::delete('/delete/{id}',        'destroy')->name('delete')->middleware('hasPermission:customer_delete');
            Route::get('/status-update/{id}',    'statusUpdate')->name('status.update')->middleware('hasPermission:customer_status_update');
            Route::get('view/{id}',              'view')->name('view')->middleware('hasPermission:customer_read'); 
            Route::get('get-all',                'getAllCustomers')->name('get.all');

            Route::get('get-invoice/{id}',         'getInvoice')->name('get.invoice');
            Route::get('get-payment-history/{id}', 'getPaymentHistory')->name('get.payment.history');
            Route::get('get-pos-invoice/{id}',         'getPosInvoice')->name('get.pos.invoice');
            Route::get('get-pos-payment-history/{id}', 'getPosPaymentHistory')->name('get.pos.payment.history');
            Route::get('get-service-sale-invoice/{id}',         'getServiceSaleInvoice')->name('get.servicesale.invoice');
            Route::get('get-service-sale-payment-history/{id}', 'getServiceSalePaymentHistory')->name('get.servicesale.payment.history');
            Route::get('get-customer',                 'getCustomer')->name('get.customer');
            //modal create
            Route::get('/create-modal',                'createModal')->name('create.modal')->middleware('hasPermission:customer_create'); 
            Route::post('/store-modal',                'storeModal')->name('store.modal')->middleware('hasPermission:customer_create');

        });
});

