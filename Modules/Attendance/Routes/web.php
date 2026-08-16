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
use Modules\Attendance\Http\Controllers\AttendanceController;

Route::middleware(['XSS','IsInstalled'])->group(function(){
    Route::middleware(['auth','isSubscribed'])
        ->prefix('hrm/employee-attendance')
        ->controller(AttendanceController::class)
        ->name('hrm.attendance.')
        ->group(function(){
            Route::get('/',                      'index')->name('index')->middleware('hasPermission:attendance_read'); 
            Route::post('/store',                'store')->name('store')->middleware('hasPermission:attendance_create'); 
            Route::get('/edit/{id}',             'edit')->name('edit.modal')->middleware('hasPermission:attendance_update'); 
            Route::put('/update',                'update')->name('update')->middleware('hasPermission:attendance_update');
            Route::delete('/delete/{id}',        'destroy')->name('delete')->middleware('hasPermission:attendance_delete');   
            Route::get('/create-modal',          'createModal')->name('create.modal')->middleware('hasPermission:attendance_create');
            Route::get('/details-modal',          'detailsModal')->name('details.modal')->middleware('hasPermission:attendance_read');
            Route::get('/checkout-modal',          'checkoutModal')->name('checkout.modal')->middleware('hasPermission:attendance_create');
            Route::get('/filter',                  'filter')->name('filter')->middleware('hasPermission:attendance_read'); 
         
        });
 

});
