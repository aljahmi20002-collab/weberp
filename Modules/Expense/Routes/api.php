<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Expense\Http\Controllers\Api\V1\ExpenseController;

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

Route::middleware('auth:api')->get('/expense', function (Request $request) {
    return $request->user();
});



Route::prefix('v1')->group(function () {
    Route::middleware(['CheckApiKey'])->group(function () { 
        //Expense routes
        Route::middleware(['auth:sanctum','ApiIsSubscribed']) 
        ->prefix('accounts/expense')
        ->controller(ExpenseController::class) 
        ->group(function(){ 
            Route::get('/',                           'index')->middleware('hasPermission:expense_read');  
            Route::get('/create',                     'create')->middleware('hasPermission:expense_create');
            Route::post('/store',                     'store')->middleware('hasPermission:expense_create');
            Route::get('/edit/{id}',                  'edit')->middleware('hasPermission:expense_update'); 
            Route::put('/update',                     'update')->middleware('hasPermission:expense_update');
            Route::delete('/delete/{id}',             'destroy')->middleware('hasPermission:expense_delete'); 
            Route::get('/branch-account/{branch_id}', 'branchAccount');   
        });
        //end Expense routes
    });
});