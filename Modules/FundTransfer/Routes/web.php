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
use Modules\FundTransfer\Http\Controllers\FundTransferController;

Route::middleware(['XSS','IsInstalled'])->group(function(){
    Route::middleware(['auth','isSubscribed'])
        ->group(function(){ 
            //Fund Transrer routes
            Route::prefix('accounts/fund-transfer')
            ->controller(FundTransferController::class)
            ->name('accounts.fund.transfer.')
            ->group(function(){ 
                Route::get('/',                       'index')->name('index')->middleware('hasPermission:fund_transfer_read');  
                Route::get('/create',                 'create')->name('create')->middleware('hasPermission:fund_transfer_create');
                Route::post('/store',                 'store')->name('store')->middleware('hasPermission:fund_transfer_create');
                Route::get('/edit/{id}',              'edit')->name('edit')->middleware('hasPermission:fund_transfer_update'); 
                Route::put('/update',                 'update')->name('update')->middleware('hasPermission:fund_transfer_update');
                Route::delete('/delete/{id}',         'destroy')->name('delete')->middleware('hasPermission:fund_transfer_delete');   
                
                Route::get('get-all',                 'getAllfundTransfer')->name('get.all');
            });
            //end Fund Transfer routes
        });
    });
  