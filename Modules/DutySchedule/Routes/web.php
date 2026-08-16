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
use Modules\DutySchedule\Http\Controllers\DutyScheduleController;

Route::middleware(['XSS','IsInstalled'])->group(function(){
    Route::middleware(['auth'])
        ->prefix('hrm/attendance/duty-schedule')
        ->controller(DutyScheduleController::class)
        ->name('hrm.attendance.duty.schedule.')
        ->group(function(){
            Route::get('/',                      'index')->name('index')->middleware('hasPermission:duty_schedule_read');
            Route::get('/create',                'create')->name('create')->middleware('hasPermission:duty_schedule_create');
            Route::post('/store',                'store')->name('store')->middleware('hasPermission:duty_schedule_create');
            Route::get('/edit/{id}',             'edit')->name('edit')->middleware('hasPermission:duty_schedule_update');
            Route::put('/update',                'update')->name('update')->middleware('hasPermission:duty_schedule_update');
            Route::delete('/delete/{id}',        'destroy')->name('delete')->middleware('hasPermission:duty_schedule_delete');
            Route::get('get-all',                'getAllDutySchedule')->name('get.all');
        });
});
