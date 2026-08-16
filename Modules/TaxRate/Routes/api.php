<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\TaxRate\Http\Controllers\Api\V1\TaxRateController;

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

Route::middleware('auth:api')->get('/taxrate', function (Request $request) {
    return $request->user();
});


Route::prefix('v1')->group(function () {
    Route::middleware(['CheckApiKey'])->group(function () {
        Route::middleware(['auth:sanctum','ApiIsSubscribed'])
            ->prefix('settings/tax-rate')
            ->controller(TaxRateController::class) 
            ->group(function () {
                Route::get('/',                      'index')->middleware('hasPermission:tax_rate_read'); 
                Route::post('/store',                'store')->middleware('hasPermission:tax_rate_create');
                Route::get('/edit/{id}',             'edit')->middleware('hasPermission:tax_rate_update');
                Route::put('/update',                'update')->middleware('hasPermission:tax_rate_update');
                Route::delete('/delete/{id}',        'destroy')->middleware('hasPermission:tax_rate_delete');
                Route::get('/status-update/{id}',    'statusUpdate')->middleware('hasPermission:tax_rate_status_update');
            });
    });
});
