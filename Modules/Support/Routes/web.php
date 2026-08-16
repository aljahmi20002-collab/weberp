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
use Modules\Support\Http\Controllers\AdminSupportController;
use Modules\Support\Http\Controllers\SupportController;
  
Route::middleware(['XSS','IsInstalled'])->group(function(){
    Route::middleware(['auth','isSubscribed'])
        ->group(function(){ 
         
            //Admin support ticket
            Route::prefix('ticket')
            ->controller(AdminSupportController::class)
            ->name('ticket.')
            ->group(function(){
                Route::get('/',                      'index')->name('index')->middleware('hasPermission:supports_read');  
                Route::get('/create',                'create')->name('create')->middleware('hasPermission:supports_create');
                Route::post('/store',                'store')->name('store')->middleware('hasPermission:supports_create');
                Route::get('/edit/{id}',             'edit')->name('edit')->middleware('hasPermission:supports_update');
                Route::put('/update',                'update')->name('update')->middleware('hasPermission:supports_update');
                Route::delete('/delete/{id}',        'destroy')->name('delete')->middleware('hasPermission:supports_delete');
                Route::get('get-ticket',             'getTicket')->name('get.ticket')->middleware('hasPermission:supports_read');  
                Route::get('status-update/{id}',     'statusUpdate')->name('status.update')->middleware('hasPermission:supports_status_update');
                Route::get('view/{id}',              'view')->name('view');
                Route::post('reply',                  'reply')->name('reply'); 
            }); 

            //business support
            Route::prefix('support')
            ->controller(SupportController::class)
            ->name('support.')
            ->group(function(){
                Route::get('/',                      'index')->name('index')->middleware('hasPermission:support_read');  
                Route::get('/create',                'create')->name('create')->middleware('hasPermission:support_create');
                Route::post('/store',                'store')->name('store')->middleware('hasPermission:support_create');
                Route::get('/edit/{id}',             'edit')->name('edit')->middleware('hasPermission:support_update');
                Route::put('/update',                'update')->name('update')->middleware('hasPermission:support_update');
                Route::delete('/delete/{id}',        'destroy')->name('delete')->middleware('hasPermission:support_delete');
                Route::get('get-support',            'getSupport')->name('get.support')->middleware('hasPermission:support_read');  
                Route::get('status-update/{id}',     'statusUpdate')->name('status.update')->middleware('hasPermission:support_status_update');
                Route::get('view/{id}',              'view')->name('view');
                Route::post('reply',                 'reply')->name('reply');
            }); 

        });
});
  
