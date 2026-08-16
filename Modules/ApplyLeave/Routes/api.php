<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\ApplyLeave\Http\Controllers\Api\V1\ApplyLeaveController;

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

Route::middleware('auth:api')->get('/applyleave', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () { 
    Route::middleware(['CheckApiKey'])->group(function () {
            Route::group(['middleware' => ['auth:sanctum','ApiIsSubscribed']], function () {  

                //apply leave 
                Route::get('leave/request',                 [ApplyLeaveController::class,   'leaveRequest']);
                Route::get('leave/request/approval/{id}',   [ApplyLeaveController::class,   'approval'])->middleware('hasPermission:leave_request_approval');
            });
        });
    });