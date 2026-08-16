<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Business\Http\Controllers\Api\V1\BusinessController;

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

Route::middleware('auth:api')->get('/business', function (Request $request) {
    return $request->user();
});

// Route::prefix('v1')->group(function() {
//     Route::middleware(['CheckApiKey'])->group(function () {
//         Route::middleware(['auth:sanctum'])
//             ->prefix('business')
//             ->controller(BusinessController::class) 
//             ->group(function(){
//                 Route::get('/',                      'index')->middleware('hasPermission:business_read');
//                 Route::get('/create',                'create')->middleware('hasPermission:business_create');
//                 Route::post('/store',                'store')->middleware('hasPermission:business_create');
//                 Route::get('/edit/{id}',             'edit')->middleware('hasPermission:business_update');
//                 Route::put('/update',                'update')->middleware('hasPermission:business_update');
//                 Route::delete('/delete/{id}',        'destroy')->middleware('hasPermission:business_delete');
//                 Route::get('/status-update/{id}',    'statusUpdate')->middleware('hasPermission:business_status_update');
//             });
//     });
// });
