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
use Modules\Product\Http\Controllers\ProductController;
Route::middleware(['XSS','IsInstalled'])->group(function(){
    Route::middleware(['auth','isSubscribed'])
        ->group(function(){  
            Route::prefix('product')
            ->controller(ProductController::class)
            ->name('product.')
            ->group(function(){
                Route::get('/',                      'index')->name('index')->middleware('hasPermission:product_read');  
                Route::get('/create',                'create')->name('create')->middleware('hasPermission:product_create');
                Route::post('/store',                'store')->name('store')->middleware('hasPermission:product_create');
                Route::get('/edit/{id}',             'edit')->name('edit')->middleware('hasPermission:product_update'); 
                Route::put('/update',                'update')->name('update')->middleware('hasPermission:product_update');
                Route::delete('/delete/{id}',        'destroy')->name('delete')->middleware('hasPermission:product_delete');
                // Route::get('/status-update/{id}',    'statusUpdate')->name('status.update')->middleware('hasPermission:product_status_update');
               //ajax routes
                Route::get('view/{id}',             'view')->name('view');
                Route::post('/category',             'category')->name('category');
                Route::post('/subcategory',          'subcategory')->name('subcategory');
                Route::post('/branches',             'branches')->name('branches');
                Route::post('applicable/tax',        'applicableTax')->name('applicable.tax');
                Route::post('brands',                'brands')->name('brands');
                Route::post('units',                 'units')->name('units');
                Route::post('variation/values',      'VariationValues')->name('variation.values');

                //duplicate
                Route::get('duplicate/create/{id}',   'duplicateCreate')->name('duplicate.create')->middleware('hasPermission:product_create');
                Route::post('duplicate/store',        'duplicateStore')->name('duplicate.store')->middleware('hasPermission:product_create');
                Route::get('labels/{id}',             'labels')->name('labels')->middleware('hasPermission:product_read');  
                Route::post('label/print',             'labelPrint')->name('label.print')->middleware('hasPermission:product_read');  

                Route::get('get-all-products',         'getAllProducts')->name('get.all.products');
                //stock alert
                Route::post('product/search',          'productSearch')->name('search');
                Route::get('stock-alert',              'stockAlert')->name('stock.alert');
                Route::post('stock-alert/search',      'stockAlertSearchProducts')->name('stock.alert.search');
                Route::get('stock-alert/getdata',      'stockAlertGetProducts')->name('stock.alert.getdata');
 
            });
        });
});
 