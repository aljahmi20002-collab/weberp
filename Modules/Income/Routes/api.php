<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Income\Http\Controllers\Api\V1\IncomeController;

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

Route::middleware('auth:api')->get('/income', function (Request $request) {
    return $request->user();
});

 
Route::prefix('v1')->group(function () {
    Route::middleware(['CheckApiKey'])->group(function () {
        //Income routes
        Route::middleware(['auth:sanctum','ApiIsSubscribed']) 
        ->prefix('accounts/income')
        ->controller(IncomeController::class) 
        ->group(function(){ 
            Route::get('/',                           'index')->middleware('hasPermission:income_read');  
            Route::get('/create',                     'create')->middleware('hasPermission:income_create');
            Route::post('/store',                     'store')->middleware('hasPermission:income_create');
            Route::get('/edit/{id}',                  'edit')->middleware('hasPermission:income_update'); 
            Route::put('/update',                     'update')->middleware('hasPermission:income_update');
            Route::delete('/delete/{id}',             'destroy')->middleware('hasPermission:income_delete'); 
            Route::get('/branch-account/{branch_id}', 'branchAccount');  
        });
        //end Income routes
    });
});