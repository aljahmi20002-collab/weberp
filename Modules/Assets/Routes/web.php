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
use Modules\Assets\Http\Controllers\AssetsController;

Route::middleware(['XSS','IsInstalled'])->group(function(){
    Route::middleware(['auth'])
        ->prefix('assets')
        ->controller(AssetsController::class)
        ->name('assets.')
        ->group(function(){
            Route::get('/',                      'index')->name('index')->middleware('hasPermission:assets_read');
            Route::get('/create',                'create')->name('create')->middleware('hasPermission:assets_create');
            Route::post('/store',                'store')->name('store')->middleware('hasPermission:assets_create');
            Route::get('/edit/{id}',             'edit')->name('edit')->middleware('hasPermission:assets_update');
            Route::put('/update',                'update')->name('update')->middleware('hasPermission:assets_update');
            Route::delete('/delete/{id}',        'destroy')->name('delete')->middleware('hasPermission:assets_delete');
            Route::get('get-all',                'getAll')->name('get.all')->middleware('hasPermission:assets_read');
        });
});


 