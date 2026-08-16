<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Purchase\Http\Controllers\Api\V1\InvoiceController;
use Modules\Purchase\Http\Controllers\Api\V1\PurchaseController;

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

Route::middleware('auth:api')->get('/purchase', function (Request $request) {
    return $request->user();
});
 
Route::prefix('v1')->group(function () { 
    Route::middleware(['CheckApiKey'])->group(function () {
            Route::group(['middleware' => ['auth:sanctum','ApiIsSubscribed']], function () {  

                Route::prefix('purchase')
                ->controller(PurchaseController::class)
                ->group(function(){
                    Route::get('/',             'index'); 
                }); 

                //invoice 
                Route::prefix('invoice/purchase')
                ->controller(InvoiceController::class)
                ->group(function(){
                    Route::get('/',             'index');
                    Route::get('/return',       'purchaseReturnIndex');
                }); 

        });
    });
});