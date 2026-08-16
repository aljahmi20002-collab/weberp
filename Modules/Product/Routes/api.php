<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Product\Http\Controllers\Api\V1\ProductController;

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

Route::middleware('auth:api')->get('/product', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function() {
    Route::middleware(['CheckApiKey'])->group(function () {
        Route::middleware(['auth:sanctum','ApiIsSubscribed'])
            ->prefix('product')
            ->controller(ProductController::class)
            ->group(function(){
                Route::get('/',                      'index')->middleware('hasPermission:product_read');
                Route::get('/create',                'create')->middleware('hasPermission:product_create');
                Route::post('/store',                'store')->middleware('hasPermission:product_create');
                Route::get('/edit/{id}',             'edit')->middleware('hasPermission:product_update');
                Route::put('/update',                'update')->middleware('hasPermission:product_update');
                Route::delete('/delete/{id}',        'destroy')->middleware('hasPermission:product_delete'); 
                Route::get('view/{id}',              'view');

                Route::get('category-wise-products/{category_id}',                      'categoryWiseProducts');
                Route::get('subcategory-wise-products/{category_id}/{subcategory_id}',  'subcategoryWiseProducts');

            });
    });
});
