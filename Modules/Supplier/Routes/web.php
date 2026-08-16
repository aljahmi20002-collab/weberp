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
use Modules\Supplier\Http\Controllers\SupplierController;

Route::middleware(['XSS','IsInstalled'])->group(function(){
    Route::middleware(['auth','isSubscribed'])
        ->prefix('suppliers')
        ->controller(SupplierController::class)
        ->name('suppliers.')
        ->group(function(){
            Route::get('/',                      'index')->name('index')->middleware('hasPermission:supplier_read'); 
            Route::get('/create',                'create')->name('create')->middleware('hasPermission:supplier_create');
            Route::post('/store',                'store')->name('store')->middleware('hasPermission:supplier_create');
            Route::get('/edit/{id}',             'edit')->name('edit')->middleware('hasPermission:supplier_update');
            Route::put('/update',                'update')->name('update')->middleware('hasPermission:supplier_update');
            Route::delete('/delete/{id}',        'destroy')->name('delete')->middleware('hasPermission:supplier_delete');
            Route::get('/status-update/{id}',    'statusUpdate')->name('status.update')->middleware('hasPermission:supplier_status_update');
            Route::get('view/{id}',               'view')->name('view')->middleware('hasPermission:supplier_read'); 
            //table data fetch
            Route::get('get-purchase-invoice/{id}',                 'getPurchaseInvoice')->name('get.purchase.invoice');
            Route::get('get-purchase-invoice-payment/{id}',         'getPurchaseInvoicePayment')->name('get.purchase.invoice.payment');
            Route::get('get-purchase-return-invoice/{id}',          'getPurchaseReturnInvoice')->name('get.purchase.return.invoice');
            Route::get('get-purchase-return-invoice-payment/{id}',  'getPurchaseReturnInvoicePayment')->name('get.purchase.return.invoice.payment');
            //create modal
            Route::get('/create-modal',           'createModal')->name('create.modal')->middleware('hasPermission:supplier_create');
            Route::post('/store-modal',           'storeModal')->name('store.modal')->middleware('hasPermission:supplier_create');
        });
});
 
