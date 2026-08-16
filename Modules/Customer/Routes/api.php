<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Customer\Http\Controllers\Api\V1\CustomerController;

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

Route::middleware('auth:api')->get('/customers', function (Request $request) {
    return $request->user();
});


Route::prefix('v1')->group(function () {
    Route::middleware(['CheckApiKey'])->group(function () {
        Route::middleware(['auth:sanctum','ApiIsSubscribed'])
            ->prefix('customers')
            ->controller(CustomerController::class)
            ->group(function () {
                Route::get('/',                      'index')->middleware('hasPermission:customer_read'); 
                Route::post('/store',                'store')->middleware('hasPermission:customer_create'); 
            });
    });
});
