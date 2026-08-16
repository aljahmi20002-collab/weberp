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
use Modules\Category\Http\Controllers\CategoryController;

Route::middleware(['XSS','IsInstalled'])->group(function(){
    Route::middleware(['auth','isSubscribed'])
        ->group(function(){
            //category
            Route::prefix('category')
            ->controller(CategoryController::class)
            ->name('category.')
            ->group(function(){
                Route::get('/',                      'index')->name('index')->middleware('hasPermission:category_read'); 
                Route::get('/create',                'create')->name('create')->middleware('hasPermission:category_create');
                Route::post('/store',                'store')->name('store')->middleware('hasPermission:category_create');
                Route::get('/edit/{id}',             'edit')->name('edit')->middleware('hasPermission:category_update');
                Route::put('/update',                'update')->name('update')->middleware('hasPermission:category_update');
                Route::delete('/delete/{id}',        'destroy')->name('delete')->middleware('hasPermission:category_delete');
                Route::get('/status-update/{id}',    'statusUpdate')->name('status.update')->middleware('hasPermission:category_status_update');
                Route::get('/parent/categories',     'parentCategories')->name('parent.categories')->middleware('hasPermission:category_create');
                Route::get('get-all',                 'getAll')->name('get.all');
            });

        });
});
 