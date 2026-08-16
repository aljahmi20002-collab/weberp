<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\FundTransfer\Http\Controllers\Api\V1\FundTransferController;

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

Route::middleware('auth:api')->get('/fundtransfer', function (Request $request) {
    return $request->user();
});




Route::prefix('v1')->group(function () {
    Route::middleware(['CheckApiKey'])->group(function () {
        //Fund Transrer routes
        Route::middleware(['auth:sanctum','ApiIsSubscribed'])
            ->prefix('accounts/fund-transfer')
            ->controller(FundTransferController::class) 
            ->group(function () {
                Route::get('/',                       'index')->middleware('hasPermission:fund_transfer_read');
                Route::get('/create',                 'create')->middleware('hasPermission:fund_transfer_create');
                Route::post('/store',                 'store')->middleware('hasPermission:fund_transfer_create');
                Route::get('/edit/{id}',              'edit')->middleware('hasPermission:fund_transfer_update');
                Route::put('/update',                 'update')->middleware('hasPermission:fund_transfer_update');
                Route::delete('/delete/{id}',         'destroy')->middleware('hasPermission:fund_transfer_delete');

                Route::get('get-all',                 'getAllfundTransfer');
            });
        //end Fund Transfer routes
    });
});
