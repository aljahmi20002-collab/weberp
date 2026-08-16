<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\ServiceSale\Http\Controllers\Api\V1\Invoicecontroller;
use Modules\ServiceSale\Http\Controllers\Api\V1\ServiceSaleController;

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

Route::middleware('auth:api')->get('/servicesale', function (Request $request) {
    return $request->user();
});
 
Route::prefix('v1')->group(function () { 
    Route::middleware(['CheckApiKey'])->group(function () {
            Route::group(['middleware' => ['auth:sanctum','ApiIsSubscribed']], function () { 


                Route::prefix('service-sale')
                ->controller(ServiceSaleController::class)
                ->group(function(){
                    Route::get('/',                       'index')->middleware('hasPermission:service_sale_read');  
                    Route::get('/create',                 'create')->middleware('hasPermission:service_sale_create');
                    Route::post('/store',                 'store')->middleware('hasPermission:service_sale_create');
                    Route::get('/edit/{id}',              'edit')->middleware('hasPermission:service_sale_update'); 
                    Route::put('/update',                 'update')->middleware('hasPermission:service_sale_update');
                    Route::delete('/delete/{id}',         'destroy')->middleware('hasPermission:service_sale_delete'); 
                    Route::get('details/{id}',            'details')->middleware('hasPermission:service_sale_read');  

                    Route::post('service/find',           'serviceFind');
                    Route::post('service-details',        'serviceDetails');
                    Route::get('get-taxrate/{id}',        'getTaxrate');
                }); 


                //invoice 
                Route::prefix('invoice/service-sale')
                ->controller(Invoicecontroller::class)
                ->group(function(){
                    Route::get('/',             'index');
                });


            });
        });
    });