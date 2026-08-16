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
use Modules\Branch\Http\Controllers\BranchController;

Route::middleware(['XSS','IsInstalled'])->group(function(){
    Route::middleware(['auth'])
        ->prefix('settings/branch')
        ->controller(BranchController::class)
        ->name('settings.branch.')
        ->group(function(){
            Route::get('/',                      'index')->name('index')->middleware('hasPermission:branch_read');
            Route::get('/create',                'create')->name('create')->middleware('hasPermission:branch_create');
            Route::post('/store',                'store')->name('store')->middleware('hasPermission:branch_create');
            Route::get('/edit/{id}',             'edit')->name('edit')->middleware('hasPermission:branch_update');
            Route::put('/update',                'update')->name('update')->middleware('hasPermission:branch_update');
            Route::delete('/delete/{id}',        'delete')->name('delete')->middleware('hasPermission:branch_delete');
            Route::get('/status-update/{id}',    'statusUpdate')->name('status.update')->middleware('hasPermission:branch_update');
        });
});
