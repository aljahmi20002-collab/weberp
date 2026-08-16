<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Brand\Http\Controllers\Api\V1\BrandController;


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

Route::middleware('auth:api')->get('/brand', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function() {
    Route::middleware(['CheckApiKey'])->group(function () {
        Route::middleware(['auth:sanctum','ApiIsSubscribed'])
            ->prefix('brand')
            ->controller(BrandController::class) 
            ->group(function(){
                Route::get('/',                      'index')->middleware('hasPermission:brand_read');
                Route::get('/create',                'create')->middleware('hasPermission:brand_create');
                Route::post('/store',                'store')->middleware('hasPermission:brand_create');
                Route::get('/edit/{id}',             'edit')->middleware('hasPermission:brand_update');
                Route::put('/update',                'update')->middleware('hasPermission:brand_update');
                Route::delete('/delete/{id}',        'destroy')->middleware('hasPermission:brand_delete');
                Route::get('/status-update/{id}',    'statusUpdate')->middleware('hasPermission:brand_status_update');
            });
    });
});
