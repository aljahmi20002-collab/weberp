<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Service\Http\Controllers\Api\V1\ServiceController;

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

Route::middleware('auth:api')->get('/service', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {
    Route::middleware(['CheckApiKey'])->group(function () {
        Route::middleware(['auth:sanctum','ApiIsSubscribed'])
            ->prefix('services')
            ->controller(ServiceController::class)
            ->group(function () {
                Route::get('/',                      'index')->middleware('hasPermission:service_read');
                Route::get('/create',                'create')->middleware('hasPermission:service_create');
                Route::post('/store',                'store')->middleware('hasPermission:service_create');
                Route::get('/edit/{id}',             'edit')->middleware('hasPermission:service_update');
                Route::put('/update',                'update')->middleware('hasPermission:service_update');
                Route::delete('/delete/{id}',        'destroy')->middleware('hasPermission:service_delete');
                Route::get('/status-update/{id}',    'statusUpdate')->middleware('hasPermission:service_status_update');
            });
    });
});
