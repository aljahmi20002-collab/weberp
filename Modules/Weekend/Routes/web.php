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
use Modules\Weekend\Http\Controllers\WeekendController;

Route::middleware(['XSS','IsInstalled'])->group(function(){
    Route::middleware(['auth'])
        ->prefix('hrm/attendance/weekend')
        ->controller(WeekendController::class)
        ->name('hrm.attendance.weekend.')
        ->group(function(){
            Route::get('/',                      'index')->name('index')->middleware('hasPermission:weekend_read');
            Route::get('/edit/{id}',             'edit')->name('edit')->middleware('hasPermission:weekend_update');
            Route::put('/update',                'update')->name('update')->middleware('hasPermission:weekend_update');
            Route::get('/status-update/{id}',    'statusUpdate')->name('status.update')->middleware('hasPermission:weekend_status_update');
            Route::get('/get-all',               'getAll')->name('get.all');
        });
});

