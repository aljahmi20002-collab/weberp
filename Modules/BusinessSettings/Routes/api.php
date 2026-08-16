<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\BusinessSettings\Http\Controllers\Api\V1\BusinessSettingsController;

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

Route::middleware('auth:api')->get('/businesssettings', function (Request $request) {
    return $request->user();
});



Route::prefix('v1')->group(function () {
    Route::middleware(['CheckApiKey'])->group(function () {
        Route::middleware(['auth:sanctum'])
            ->controller(BusinessSettingsController::class)
            ->prefix('business-settings') 
            ->group(function(){
                Route::put('/update',               'businessInfoUpdate')->name('index');
                // Route::get('get-branch',         'getBranch')->name('get.branch');
                // Route::get('get-taxrate',        'getTaxRate')->name('get.taxrate');
                // Route::get('get-accounthead',    'getAccountHead')->name('get.accounthead');
                // Route::get('get-barcodesettings','getbarcodesettings')->name('get.barcodesettings');
                // Route::put('/update',  'update')->name('update');
            });
        });
    });