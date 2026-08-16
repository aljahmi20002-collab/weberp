<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Warranties\Http\Controllers\Api\V1\WarrantiesController;


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

Route::middleware('auth:api')->get('/warranties', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {
    Route::middleware(['CheckApiKey'])->group(function () {
        Route::middleware(['auth:sanctum','ApiIsSubscribed'])
            ->prefix('warranty')
            ->controller(WarrantiesController::class)
            ->group(function () {
                Route::get('/',                      'index')->middleware('hasPermission:warranty_read');
                Route::get('/create',                'create')->middleware('hasPermission:warranty_create');
                Route::post('/store',                'store')->middleware('hasPermission:warranty_create');
                Route::get('/edit/{id}',             'edit')->middleware('hasPermission:warranty_update');
                Route::put('/update',                'update')->middleware('hasPermission:warranty_update');
                Route::delete('/delete/{id}',        'destroy')->middleware('hasPermission:warranty_delete');
                Route::get('/status-update/{id}',    'statusUpdate')->middleware('hasPermission:warranty_status_update');
            });
    });
});
