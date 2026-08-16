<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Designation\Http\Controllers\Api\V1\DesignationController;


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

Route::middleware('auth:api')->get('/designation', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function() {
    Route::middleware(['CheckApiKey'])->group(function () {
        Route::middleware(['auth:sanctum','ApiIsSubscribed'])
            ->prefix('hrm/designation')
            ->controller(DesignationController::class)
            ->group(function(){
                Route::get('/',                      'index')->middleware('hasPermission:designation_read');
                Route::post('/store',                'store')->middleware('hasPermission:designation_create');
                Route::get('/edit/{id}',             'edit')->middleware('hasPermission:designation_update');
                Route::put('/update',                'update')->middleware('hasPermission:designation_update');
                Route::delete('/delete/{id}',        'destroy')->middleware('hasPermission:designation_delete');
                Route::get('/status-update/{id}',    'statusUpdate')->middleware('hasPermission:designation_status_update');
            });
    });
});
