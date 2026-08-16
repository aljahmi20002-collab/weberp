<?php

use App\Http\Controllers\Api\V1\SaleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Sell\Http\Controllers\Api\V1\InvoiceController;

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

Route::middleware('auth:api')->get('/sell', function (Request $request) {
    return $request->user();
});




Route::prefix('v1')->group(function () {
    Route::middleware(['CheckApiKey'])->group(function () {
        Route::middleware(['auth:sanctum','ApiIsSubscribed']) 
            ->group(function(){ 
 
                Route::controller(SaleController::class)
                ->prefix('sale')
                ->group(function(){
  
                });

                //invoice 
                Route::prefix('invoice/sale')
                ->controller(InvoiceController::class)
                ->group(function(){
                    Route::get('/',             'index');
                });
 
            });
            //end pos routes
        });
    });