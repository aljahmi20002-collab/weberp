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
use Modules\Holiday\Http\Controllers\HolidayController;

Route::middleware(['XSS','IsInstalled'])->group(function(){
    Route::middleware(['auth'])
        ->prefix('hrm/attendance/holiday')
        ->controller(HolidayController::class)
        ->name('hrm.attendance.holiday.')
        ->group(function(){
            Route::get('/',                      'index')->name('index')->middleware('hasPermission:holiday_read');
            Route::get('/create',                'create')->name('create')->middleware('hasPermission:holiday_create');
            Route::post('/store',                'store')->name('store')->middleware('hasPermission:holiday_create');
            Route::get('/edit/{id}',             'edit')->name('edit')->middleware('hasPermission:holiday_update');
            Route::put('/update',                'update')->name('update')->middleware('hasPermission:holiday_update');
            Route::delete('/delete/{id}',        'destroy')->name('delete')->middleware('hasPermission:holiday_delete');
            Route::get('/status-update/{id}',    'statusUpdate')->name('status.update')->middleware('hasPermission:holiday_status_update');
            Route::get('get-all',                'getAllHoliday')->name('get.all');
        });
});

