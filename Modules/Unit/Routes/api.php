<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Unit\Http\Controllers\Api\V1\UnitController;


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

Route::middleware('auth:api')->get('/unit', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {
    Route::middleware(['CheckApiKey'])->group(function () {
        Route::middleware(['auth:sanctum','ApiIsSubscribed'])
            ->prefix('units')
            ->controller(UnitController::class)
            ->group(function () {
                Route::get('/',                      'index')->middleware('hasPermission:unit_read');
                Route::get('/create',                'create')->middleware('hasPermission:unit_create');
                Route::post('/store',                'store')->middleware('hasPermission:unit_create');
                Route::get('/edit/{id}',             'edit')->middleware('hasPermission:unit_update');
                Route::put('/update',                'update')->middleware('hasPermission:unit_update');
                Route::delete('/delete/{id}',        'destroy')->middleware('hasPermission:unit_delete');
                Route::get('/status-update/{id}',    'statusUpdate')->middleware('hasPermission:unit_status_update');
            });
    });
});
