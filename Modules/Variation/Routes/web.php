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
use Modules\Variation\Http\Controllers\VariationController;

Route::middleware(['XSS','IsInstalled'])->group(function(){
    Route::middleware(['auth','isSubscribed'])
        ->group(function(){  
            Route::prefix('variation')
            ->controller(VariationController::class)
            ->name('variation.')
            ->group(function(){
                Route::get('/',                      'index')->name('index')->middleware('hasPermission:variation_read');  
                Route::get('/create',                'create')->name('create')->middleware('hasPermission:variation_create');
                Route::post('/store',                'store')->name('store')->middleware('hasPermission:variation_create');
                Route::get('/edit/{id}',             'edit')->name('edit')->middleware('hasPermission:variation_update');
                Route::put('/update',                'update')->name('update')->middleware('hasPermission:variation_update');
                Route::delete('/delete/{id}',        'destroy')->name('delete')->middleware('hasPermission:variation_delete');
                Route::get('/status-update/{id}',    'statusUpdate')->name('status.update')->middleware('hasPermission:variation_status_update');
                Route::get('get-all',                 'getAll')->name('get.all');
            });
        });
});
 