<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Branch\Http\Controllers\Api\V1\BranchController;

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

Route::middleware('auth:api')->get('/branch', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function() {
    Route::middleware(['CheckApiKey'])->group(function () {
        Route::middleware(['auth:sanctum','ApiIsSubscribed'])
            ->prefix('settings/branch')
            ->controller(BranchController::class) 
            ->group(function(){
                Route::get('/',                      'index')->middleware('hasPermission:branch_read');
                Route::get('/create',                'create')->middleware('hasPermission:branch_create');
                Route::post('/store',                'store')->middleware('hasPermission:branch_create');
                Route::get('/edit/{id}',             'edit')->middleware('hasPermission:branch_update');
                Route::put('/update',                'update')->middleware('hasPermission:branch_update');
                Route::delete('/delete/{id}',        'delete')->middleware('hasPermission:branch_delete');
                Route::get('/status-update/{id}',    'statusUpdate')->middleware('hasPermission:branch_update');
            });
    });
});
