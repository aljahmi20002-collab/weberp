<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Department\Http\Controllers\Api\V1\DepartmentController;

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

Route::middleware('auth:api')->get('/department', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function() {
    Route::middleware(['CheckApiKey'])->group(function () {
        Route::middleware(['auth:sanctum','ApiIsSubscribed'])
            ->prefix('hrm/department')
            ->controller(DepartmentController::class) 
            ->group(function(){
                Route::get('/',                      'index')->middleware('hasPermission:department_read');
                Route::post('/store',                'store')->middleware('hasPermission:department_create');
                Route::get('/edit/{id}',             'edit')->middleware('hasPermission:department_update');
                Route::put('/update',               'update')->middleware('hasPermission:department_update');
                Route::delete('/delete/{id}',        'destroy')->middleware('hasPermission:department_delete');
                Route::get('/status-update/{id}',    'statusUpdate')->middleware('hasPermission:department_status_update');
            });
    });
});
