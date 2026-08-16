<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Variation\Http\Controllers\Api\V1\VariationController;

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

Route::middleware('auth:api')->get('/variation', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {
    Route::middleware(['CheckApiKey'])->group(function () {
        Route::middleware(['auth:sanctum','ApiIsSubscribed'])
            ->prefix('variation')
            ->controller(VariationController::class)
            ->group(function () {
                Route::get('/',                      'index')->middleware('hasPermission:variation_read'); 
                Route::post('/store',                'store')->middleware('hasPermission:variation_create');
                Route::get('/edit/{id}',             'edit')->middleware('hasPermission:variation_update');
                Route::put('/update',                'update')->middleware('hasPermission:variation_update');
                Route::delete('/delete/{id}',        'destroy')->middleware('hasPermission:variation_delete');
                Route::get('/status-update/{id}',    'statusUpdate')->middleware('hasPermission:variation_status_update');
            });
    });
});
