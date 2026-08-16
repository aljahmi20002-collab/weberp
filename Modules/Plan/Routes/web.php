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
use Modules\Plan\Http\Controllers\PlanController;

Route::middleware(['XSS','IsInstalled'])->group(function(){
    Route::middleware(['auth'])
        ->prefix('plans')
        ->controller(PlanController::class)
        ->name('plans.')
        ->group(function(){
            Route::get('/',                      'index')->name('index')->middleware('hasPermission:plans_read');
            Route::get('/options',               'options')->name('options')->middleware('hasPermission:plans_read');
            Route::get('/create',                'create')->name('create')->middleware('hasPermission:plans_create');
            Route::post('/store',                'store')->name('store')->middleware('hasPermission:plans_create');
            Route::get('/edit/{id}',             'edit')->name('edit')->middleware('hasPermission:plans_update');
            Route::put('/update',                'update')->name('update')->middleware('hasPermission:plans_update');
            Route::delete('/delete/{id}',        'destroy')->name('delete')->middleware('hasPermission:plans_delete');
            Route::get('/status-update/{id}',    'statusUpdate')->name('status.update')->middleware('hasPermission:plans_status_update');
            Route::put('/default/{id}',          'addDefault')->name('default')->middleware('hasPermission:plans_update');
            Route::get('get-all',                 'getAllPlans')->name('get.all');
        });
});

