<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;
use Modules\LeaveAssign\Http\Controllers\LeaveAssignController;

Route::middleware(['XSS','IsInstalled'])->group(function(){
    Route::middleware(['auth','isSubscribed'])
        ->prefix('hrm/leave-assign')
        ->controller(LeaveAssignController::class)
        ->name('hrm.leave.assign.')
        ->group(function(){
            Route::get('/',                      'index')->name('index')->middleware('hasPermission:leave_assign_read');
            Route::get('/create',                'create')->name('create')->middleware('hasPermission:leave_assign_create');
            Route::post('/store',                'store')->name('store')->middleware('hasPermission:leave_assign_create');
            Route::get('/edit/{id}',             'edit')->name('edit')->middleware('hasPermission:leave_assign_update');
            Route::put('/update',                'update')->name('update')->middleware('hasPermission:leave_assign_update');
            Route::delete('/delete/{id}',        'destroy')->name('delete')->middleware('hasPermission:leave_assign_delete');
            Route::get('/status-update/{id}',    'statusUpdate')->name('status.update')->middleware('hasPermission:leave_assign_status_update');
            Route::get('get-all',                 'getAllLeaveAssign')->name('get.all');
        });
});
