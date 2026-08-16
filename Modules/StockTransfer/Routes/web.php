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
use Modules\StockTransfer\Http\Controllers\StockTransferController;

Route::middleware(['XSS','IsInstalled'])->group(function(){
    Route::middleware(['auth','isSubscribed'])
        ->group(function(){ 
            //Stock Transfer routes
            Route::prefix('stock-transfer')
            ->controller(StockTransferController::class)
            ->name('stock.transfer.')
            ->group(function(){ 
                Route::get('/',                       'index')->name('index')->middleware('hasPermission:stock_transfer_read');  
                Route::get('/create',                 'create')->name('create')->middleware('hasPermission:stock_transfer_create');
                Route::post('/store',                 'store')->name('store')->middleware('hasPermission:stock_transfer_create');
                Route::get('/edit/{id}',              'edit')->name('edit')->middleware('hasPermission:stock_transfer_update'); 
                Route::put('/update',                 'update')->name('update')->middleware('hasPermission:stock_transfer_update');
                Route::delete('/delete/{id}',         'destroy')->name('delete')->middleware('hasPermission:stock_transfer_delete');  
                Route::post('variation/location/find','VariationLocationFind')->name('variation.location.find');
                Route::post('variation-location-item','VariationLocationItem')->name('variation.location.item'); 
                Route::get('details/{id}',            'details')->name('details')->middleware('hasPermission:stock_transfer_read'); 
                Route::get('status-update/{id}',       'StatusUpdate')->name('status.update')->middleware('hasPermission:stock_transfer_status_update'); 
                Route::get('get-all',                  'getAllTransfer')->name('get.all');
            });
            //end Stock Transfer routes
        });
    });