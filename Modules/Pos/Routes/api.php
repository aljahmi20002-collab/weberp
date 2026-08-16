<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Pos\Http\Controllers\Api\V1\InvoiceController;
use Modules\Pos\Http\Controllers\Api\V1\PosController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/pos', function (Request $request) {
    return $request->user();
});




Route::prefix('v1')->group(function () {
    Route::middleware(['CheckApiKey'])->group(function () {
        Route::middleware(['auth:sanctum','ApiIsSubscribed']) 
            ->group(function(){ 
                Route::controller(PosController::class)
                ->prefix('pos')
                ->group(function(){
                    Route::get('/',                         'index')->middleware('hasPermission:pos_read');  
                    Route::get('/create',                   'create')->middleware('hasPermission:pos_create');  
                    Route::get('/product/items',            'productItems')->middleware('hasPermission:pos_create');  
                    Route::post('/pricing',                 'pricing')->middleware('hasPermission:pos_create');  
                    Route::post('/store',                   'store')->middleware('hasPermission:pos_create'); 
                    // Route::get('/products',                'products')->name('products');
                    // Route::get('list',                      'list')->name('list')->middleware('hasPermission:pos_read'); 
                    // Route::get('/edit/{id}',                'edit')->name('edit')->middleware('hasPermission:pos_update'); 
                    // Route::put('/update',                   'update')->name('update')->middleware('hasPermission:pos_update');
                    // Route::delete('/delete/{id}',           'destroy')->name('delete')->middleware('hasPermission:pos_delete'); 
                    // Route::get('details/{id}',              'details')->name('details')->middleware('hasPermission:pos_read');  
    
                    // Route::post('variation/location/find',     'VariationLocationFind')->name('variation.location.find');
                    // Route::post('variation-location-item',     'VariationLocationItem')->name('variation.location.item');
                    // Route::post('variation-location-item-get', 'VariationLocationItemGet')->name('variation.location.item.get');
                    // Route::post('get-taxrate',                 'getTaxrate')->name('taxrate.get');
                    // Route::get('get-all-pos',                  'getAllPos')->name('get.all.pos');
                });

                //invoice 
                Route::prefix('invoice/pos')
                ->controller(InvoiceController::class)
                ->group(function(){
                    Route::get('/',             'getInvoice');
                });
 
            });
            //end pos routes
        });
    });