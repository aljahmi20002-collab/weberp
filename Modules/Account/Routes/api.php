<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Account\Http\Controllers\Api\V1\AccountController;

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

Route::middleware('auth:api')->get('/account', function (Request $request) {
    return $request->user();
});


Route::prefix('v1')->group(function () {
    Route::middleware(['CheckApiKey'])->group(function () {
        Route::middleware(['auth:sanctum','ApiIsSubscribed'])
            ->prefix('account')
            ->controller(AccountController::class)
            ->group(function () {
                //Account routes
                Route::get('/',                       'index')->middleware('hasPermission:account_read');
                Route::post('/store',                 'store')->middleware('hasPermission:account_create');
                Route::get('/edit/{id}',              'edit')->middleware('hasPermission:account_update');
                Route::put('/update',                 'update')->middleware('hasPermission:account_update');
                Route::delete('/delete/{id}',         'destroy')->middleware('hasPermission:account_delete');
                Route::get('/status-update/{id}',     'statusUpdate')->middleware('hasPermission:account_status_update');
                Route::put('make-default/{id}',       'makeDefault')->middleware('hasPermission:account_create');
                Route::get('/bank-transaction',       'getbankTransaction')->middleware('hasPermission:bank_transaction_read');
            });

        //end Account routes 
    });
});
