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
use Modules\Brand\Http\Controllers\BrandController;

Route::middleware(['XSS','IsInstalled'])->group(function(){
    Route::middleware(['auth','isSubscribed'])
        ->group(function(){  
                Route::prefix('brand')
                ->controller(BrandController::class)
                ->name('brand.')
                ->group(function(){
                    Route::get('/',                      'index')->name('index')->middleware('hasPermission:brand_read');  
                    Route::get('/create',                'create')->name('create')->middleware('hasPermission:brand_create');
                    Route::post('/store',                'store')->name('store')->middleware('hasPermission:brand_create');
                    Route::get('/edit/{id}',             'edit')->name('edit')->middleware('hasPermission:brand_update');
                    Route::put('/update',                'update')->name('update')->middleware('hasPermission:brand_update');
                    Route::delete('/delete/{id}',        'destroy')->name('delete')->middleware('hasPermission:brand_delete');
                    Route::get('/status-update/{id}',    'statusUpdate')->name('status.update')->middleware('hasPermission:brand_status_update');
                    Route::get('get-all',                 'getAllBrand')->name('get.all');
                });
        });
});
 