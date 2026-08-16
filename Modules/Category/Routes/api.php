<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Category\Http\Controllers\Api\V1\CategoryController;

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

Route::middleware('auth:api')->get('/category', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function() {
    Route::middleware(['CheckApiKey'])->group(function () {
        Route::middleware(['auth:sanctum','ApiIsSubscribed'])
            ->prefix('category')
            ->controller(CategoryController::class) 
            ->group(function(){
                Route::get('/',                      'index')->middleware('hasPermission:category_read');
                Route::get('/create',                'create')->middleware('hasPermission:category_create');
                Route::post('/store',                'store')->middleware('hasPermission:category_create');
                Route::get('/edit/{id}',             'edit')->middleware('hasPermission:category_update');
                Route::put('/update',                'update')->middleware('hasPermission:category_update');
                Route::delete('/delete/{id}',        'destroy')->middleware('hasPermission:category_delete');
                Route::get('/status-update/{id}',    'statusUpdate')->middleware('hasPermission:category_status_update');
                Route::get('/parent/categories',     'parentCategories');
                Route::get('/subcategories/{category_id}',  'subCategories');
            });
    });
});
