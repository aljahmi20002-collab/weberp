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
use Modules\Warranties\Http\Controllers\WarrantiesController;

Route::middleware(['XSS','IsInstalled'])->group(function(){
    Route::middleware(['auth','isSubscribed'])
        ->group(function(){  
            Route::prefix('warranty')
            ->controller(WarrantiesController::class)
            ->name('warranty.')
            ->group(function(){
                Route::get('/',                      'index')->name('index')->middleware('hasPermission:warranty_read');  
                Route::get('/create',                'create')->name('create')->middleware('hasPermission:warranty_create');
                Route::post('/store',                'store')->name('store')->middleware('hasPermission:warranty_create');
                Route::get('/edit/{id}',             'edit')->name('edit')->middleware('hasPermission:warranty_update');
                Route::put('/update',                'update')->name('update')->middleware('hasPermission:warranty_update');
                Route::delete('/delete/{id}',        'destroy')->name('delete')->middleware('hasPermission:warranty_delete');
                Route::get('/status-update/{id}',    'statusUpdate')->name('status.update')->middleware('hasPermission:warranty_status_update');
                Route::get('get-all',                 'getAll')->name('get.all');
            });
        });
});
 
