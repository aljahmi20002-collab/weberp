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
use Modules\Designation\Http\Controllers\DesignationController;

Route::middleware(['XSS','IsInstalled'])->group(function(){
    Route::middleware(['auth'])
        ->prefix('hrm/designation')
        ->controller(DesignationController::class)
        ->name('hrm.designation.')
        ->group(function(){
            Route::get('/',                      'index')->name('index')->middleware('hasPermission:designation_read');
            Route::get('/create',                'create')->name('create')->middleware('hasPermission:designation_create');
            Route::post('/store',                'store')->name('store')->middleware('hasPermission:designation_create');
            Route::get('/edit/{id}',             'edit')->name('edit')->middleware('hasPermission:designation_update');
            Route::put('/update',                'update')->name('update')->middleware('hasPermission:designation_update');
            Route::delete('/delete/{id}',        'destroy')->name('delete')->middleware('hasPermission:designation_delete');
            Route::get('/status-update/{id}',    'statusUpdate')->name('status.update')->middleware('hasPermission:designation_status_update');
            Route::get('get-all',                 'getAllDesignation')->name('get.all');
        });
});
