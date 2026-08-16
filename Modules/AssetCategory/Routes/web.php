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
use Modules\AssetCategory\Http\Controllers\AssetCategoryController;

Route::middleware(['XSS','IsInstalled'])->group(function(){
    Route::middleware(['auth'])
        ->prefix('settings/assetcategory')
        ->controller(AssetCategoryController::class)
        ->name('settings.assetcategory.')
        ->group(function(){
            Route::get('/',                      'index')->name('index')->middleware('hasPermission:asset_category_read');
            Route::get('/create',                'create')->name('create')->middleware('hasPermission:asset_category_create');
            Route::post('/store',                'store')->name('store')->middleware('hasPermission:asset_category_create');
            Route::get('/edit/{id}',             'edit')->name('edit')->middleware('hasPermission:asset_category_update');
            Route::put('/update',                'update')->name('update')->middleware('hasPermission:asset_category_update');
            Route::delete('/delete/{id}',        'destroy')->name('delete')->middleware('hasPermission:asset_category_delete');
            Route::get('/status-update/{id}',    'statusUpdate')->name('status.update')->middleware('hasPermission:asset_category_status_update');
            Route::get('get-all',                 'getAll')->name('get.all')->middleware('hasPermission:asset_category_read');
        });
});
 