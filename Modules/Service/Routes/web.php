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
use Modules\Service\Http\Controllers\ServiceController;

Route::middleware(['XSS','IsInstalled'])->group(function(){
    Route::middleware(['auth','isSubscribed'])
        ->prefix('services')
        ->controller(ServiceController::class)
        ->name('services.')
        ->group(function(){
            Route::get('/',                      'index')->name('index')->middleware('hasPermission:service_read'); 
            Route::get('/create',                'create')->name('create')->middleware('hasPermission:service_create');
            Route::post('/store',                'store')->name('store')->middleware('hasPermission:service_create');
            Route::get('/edit/{id}',             'edit')->name('edit')->middleware('hasPermission:service_update');
            Route::put('/update',                'update')->name('update')->middleware('hasPermission:service_update');
            Route::delete('/delete/{id}',        'destroy')->name('delete')->middleware('hasPermission:service_delete');
            Route::get('/status-update/{id}',    'statusUpdate')->name('status.update')->middleware('hasPermission:service_status_update'); 
            Route::get('get-all',                'getAll')->name('data');
        });
});
 