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
use Modules\AccountHead\Http\Controllers\AccountHeadController;

Route::middleware(['XSS','IsInstalled'])->group(function(){
    Route::middleware(['auth','isSubscribed'])
        ->group(function(){ 
            //Account Head routes
            Route::prefix('head-of-account')
            ->controller(AccountHeadController::class)
            ->name('accounts.account.head.')
            ->group(function(){ 
                Route::get('/',                       'index')->name('index')->middleware('hasPermission:account_head_read');  
                Route::get('/create',                 'create')->name('create')->middleware('hasPermission:account_head_create');
                Route::post('/store',                 'store')->name('store')->middleware('hasPermission:account_head_create');
                Route::get('/edit/{id}',              'edit')->name('edit')->middleware('hasPermission:account_head_update'); 
                Route::put('/update',                 'update')->name('update')->middleware('hasPermission:account_head_update');
                Route::delete('/delete/{id}',         'destroy')->name('delete')->middleware('hasPermission:account_head_delete'); 
                Route::get('/status-update/{id}',    'statusUpdate')->name('status.update')->middleware('hasPermission:account_head_status_update');
             });
            //end Account Head routes
        });
    });
 