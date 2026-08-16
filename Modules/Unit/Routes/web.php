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
use Modules\Unit\Http\Controllers\UnitController;

Route::middleware(['XSS','IsInstalled'])->group(function(){
    Route::middleware(['auth','isSubscribed'])
        ->group(function(){  
            Route::prefix('units')
            ->controller(UnitController::class)
            ->name('units.')
            ->group(function(){
                Route::get('/',                      'index')->name('index')->middleware('hasPermission:unit_read');  
                Route::get('/create',                'create')->name('create')->middleware('hasPermission:unit_create');
                Route::post('/store',                'store')->name('store')->middleware('hasPermission:unit_create');
                Route::get('/edit/{id}',             'edit')->name('edit')->middleware('hasPermission:unit_update');
                Route::put('/update',                'update')->name('update')->middleware('hasPermission:unit_update');
                Route::delete('/delete/{id}',        'destroy')->name('delete')->middleware('hasPermission:unit_delete');
                Route::get('/status-update/{id}',    'statusUpdate')->name('status.update')->middleware('hasPermission:unit_status_update');
                Route::get('get-all',                'getAll')->name('get.all');
                
            });
        });
});
  