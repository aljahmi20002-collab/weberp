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
use Modules\ApplyLeave\Http\Controllers\ApplyLeaveController;

Route::middleware(['XSS','IsInstalled'])->group(function(){
    Route::middleware(['auth','isSubscribed'])
        ->prefix('hrm/apply-leave')
        ->controller(ApplyLeaveController::class)
        ->name('hrm.apply.leave.')
        ->group(function(){
            Route::get('/',                      'index')->name('index')->middleware('hasPermission:apply_leave_read');
            Route::get('/create',                'create')->name('create')->middleware('hasPermission:apply_leave_create');
            Route::post('/store',                'store')->name('store')->middleware('hasPermission:apply_leave_create');
            Route::delete('/delete/{id}',        'destroy')->name('delete')->middleware('hasPermission:apply_leave_delete');
            Route::post('/assigned-leave',       'AssignedLeave')->name('assigned.leave');
            Route::get('get-all-applied-leave',  'allAppliedLeave')->name('get.all.applied.leave');
            Route::get('get-pending-applied-leave',  'PendingAppliedLeave')->name('get.pending.applied.leave');
            Route::get('get-approved-applied-leave',  'ApprovedAppliedLeave')->name('get.approved.applied.leave');
            Route::get('get-rejected-applied-leave',  'rejectedAppliedLeave')->name('get.rejected.applied.leave');
        });
    //available leave
    Route::middleware(['auth','isSubscribed'])
        ->prefix('hrm/available-leave')
        ->controller(ApplyLeaveController::class)
        ->name('hrm.available.leave.')
        ->group(function(){
            Route::get('/',                      'AvailableLeave')->name('index')->middleware('hasPermission:available_leave_read');
            Route::get('get-all',                'getAllAvailableLeave')->name('get.all');
        });

        //leave request route
    Route::middleware(['auth','isSubscribed'])
        ->prefix('hrm/leave-request')
        ->controller(ApplyLeaveController::class)
        ->name('hrm.leave.request.')
        ->group(function(){
            Route::get('/',                      'leaveRequestList')->name('index')->middleware('hasPermission:leave_request_read');
            Route::get('get-all',                 'getAllLeaveRequest')->name('get.all.request');
            Route::get('/print/{id}',            'leaveRequestPrint')->name('print')->middleware('hasPermission:leave_request_read');
            Route::get('/details/{id}',           'Requestdetails')->name('details')->middleware('hasPermission:leave_request_read');
            Route::get('/approval/{id}',         'approval')->name('approval')->middleware('hasPermission:leave_request_approval');
        });


});

