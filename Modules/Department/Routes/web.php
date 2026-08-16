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
use Modules\Department\Http\Controllers\DepartmentController;

Route::middleware(['XSS','IsInstalled'])->group(function(){
    Route::middleware(['auth'])
        ->prefix('hrm/department')
        ->controller(DepartmentController::class)
        ->name('hrm.department.')
        ->group(function(){
            Route::get('/',                      'index')->name('index')->middleware('hasPermission:department_read');
            Route::get('/create',                'create')->name('create')->middleware('hasPermission:department_create');
            Route::post('/store',                'store')->name('store')->middleware('hasPermission:department_create');
            Route::get('/edit/{id}',             'edit')->name('edit')->middleware('hasPermission:department_update');
            Route::put('/update',                'update')->name('update')->middleware('hasPermission:department_update');
            Route::delete('/delete/{id}',        'destroy')->name('delete')->middleware('hasPermission:department_delete');
            Route::get('/status-update/{id}',    'statusUpdate')->name('status.update')->middleware('hasPermission:department_status_update');
            Route::get('get-all',                 'getAllDepartment')->name('get.all');
        });
});
