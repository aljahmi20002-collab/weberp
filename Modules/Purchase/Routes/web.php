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
use Modules\Purchase\Http\Controllers\PurchaseController;
use Modules\Purchase\Http\Controllers\PurchaseReturnController;
use Modules\Sell\Http\Controllers\InvoiceController;

Route::middleware(['XSS','IsInstalled'])->group(function(){
    Route::middleware(['auth','isSubscribed'])
        ->group(function(){  
            Route::prefix('purchase')
            ->controller(PurchaseController::class)
            ->name('purchase.')
            ->group(function(){
                Route::get('/',                       'index')->name('index')->middleware('hasPermission:purchase_read');  
                Route::get('/create',                 'create')->name('create')->middleware('hasPermission:purchase_create');
                Route::post('/store',                 'store')->name('store')->middleware('hasPermission:purchase_create');
                Route::get('/edit/{id}',              'edit')->name('edit')->middleware('hasPermission:purchase_update'); 
                Route::put('/update',                 'update')->name('update')->middleware('hasPermission:purchase_update');
                Route::delete('/delete/{id}',         'destroy')->name('delete')->middleware('hasPermission:purchase_delete');  
                Route::post('variation/location/find','VariationLocationFind')->name('variation.location.find');
                Route::post('variation-location-item','VariationLocationItem')->name('variation.location.item');
                Route::post('get-taxrate',            'getTaxrate')->name('taxrate.get');
                Route::get('details/{id}',            'details')->name('details')->middleware('hasPermission:purchase_read');  
                Route::get('manage/payment/{id}',     'managePayment')->name('manage.payment')->middleware('hasPermission:purchase_read_payment');  
                Route::post('add/payment',            'addPayment')->name('add.payment')->middleware('hasPermission:purchase_add_payment');  
                Route::get('edit/payment/{id}',       'editPayment')->name('edit.payment')->middleware('hasPermission:purchase_update_payment');  
                Route::post('update/payment',         'updatePayment')->name('update.payment')->middleware('hasPermission:purchase_update_payment');  
                Route::get('delete/payment/{id}',     'deletePayment')->name('delete.payment')->middleware('hasPermission:purchase_delete_payment');  
                Route::get('invoice/{id}',             'invoiceView')->name('invoice.view');
                Route::get('invoice-get-all',          [InvoiceController::class,'getAllPurchaseInvoice'])->name('invoice.get.all');
                Route::get('get-all',                  'getAllPurchase')->name('get.all');
                Route::put('status-update/{id}/{status}',  'statusUpdate')->name('status.update');
            });
 
            //purchase return routes
            Route::prefix('return/purchase')
            ->controller(PurchaseReturnController::class)
            ->name('purchase.return.')
            ->group(function(){
                Route::get('/',                       'index')->name('index')->middleware('hasPermission:purchase_return_read');  
                Route::get('/create',                 'create')->name('create')->middleware('hasPermission:purchase_return_create');
                Route::post('/store',                 'store')->name('store')->middleware('hasPermission:purchase_return_create');
                Route::get('/edit/{id}',              'edit')->name('edit')->middleware('hasPermission:purchase_return_update'); 
                Route::put('/update',                 'update')->name('update')->middleware('hasPermission:purchase_return_update');
                Route::delete('/delete/{id}',         'destroy')->name('delete')->middleware('hasPermission:purchase_return_delete');
                Route::post('variation/location/find','VariationLocationFind')->name('variation.location.find');  
                Route::post('variation-location-item','VariationLocationItem')->name('variation.location.item'); 
                Route::get('details/{id}',            'details')->name('details')->middleware('hasPermission:purchase_return_read');  
                Route::get('manage/payment/{id}',     'managePayment')->name('manage.payment')->middleware('hasPermission:purchase_return_read_payment');  
                Route::post('add/payment',            'addPayment')->name('add.payment')->middleware('hasPermission:purchase_return_add_payment');  
                Route::get('edit/payment/{id}',       'editPayment')->name('edit.payment')->middleware('hasPermission:purchase_return_update_payment');  
                Route::post('update/payment',         'updatePayment')->name('update.payment')->middleware('hasPermission:purchase_return_update_payment');  
                Route::get('delete/payment/{id}',     'deletePayment')->name('delete.payment')->middleware('hasPermission:purchase_return_delete_payment');  
                Route::get('invoice/{id}',             'invoiceView')->name('invoice.view');
                Route::get('invoice-get-all',          [InvoiceController::class,'getAllPurchaseReturnInvoice'])->name('invoice.get.all');
                Route::get('get-all',                  'getAllPurchaseReturn')->name('get.all');
            });
            //end purchase return routes
        });
});
 