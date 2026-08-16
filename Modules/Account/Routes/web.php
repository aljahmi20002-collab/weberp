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
use Modules\Account\Http\Controllers\AccountController;

Route::middleware(['XSS','IsInstalled'])->group(function(){
    Route::middleware(['auth','isSubscribed'])
        ->group(function(){ 
            //Account routes
            Route::prefix('accounts/account')
            ->controller(AccountController::class)
            ->name('accounts.account.')
            ->group(function(){ 
                Route::get('/',                       'index')->name('index')->middleware('hasPermission:account_read');  
                Route::get('/create',                 'create')->name('create')->middleware('hasPermission:account_create');
                Route::post('/store',                 'store')->name('store')->middleware('hasPermission:account_create');
                Route::get('/edit/{id}',              'edit')->name('edit')->middleware('hasPermission:account_update'); 
                Route::put('/update',                 'update')->name('update')->middleware('hasPermission:account_update');
                Route::delete('/delete/{id}',         'destroy')->name('delete')->middleware('hasPermission:account_delete');  
                Route::get('/status-update/{id}',     'statusUpdate')->name('status.update')->middleware('hasPermission:account_status_update');  
                Route::get('get-all',                 'getAllAccounts')->name('get.all');
                Route::put('make-default/{id}',      'makeDefault')->name('make.default')->middleware('hasPermission:account_create');
            });
            Route::prefix('accounts')
            ->controller(AccountController::class)
            ->name('accounts.account.')
            ->group(function(){ 
                Route::get('/bank-transaction',       'bankTransaction')->name('bank.transaction')->middleware('hasPermission:bank_transaction_read');  
                Route::get('/get-bank-transaction',       'getbankTransaction')->name('get.bank.transaction');  
            });
            //end Account routes
        });
    });
 