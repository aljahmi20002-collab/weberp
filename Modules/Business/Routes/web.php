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
use Modules\Business\Http\Controllers\BusinessBranchController;
use Modules\Business\Http\Controllers\BusinessController;

Route::middleware(['XSS','IsInstalled'])->group(function(){
    Route::middleware(['auth'])
        ->prefix('business')
        ->controller(BusinessController::class)
        ->name('business.')
        ->group(function(){
            Route::get('/',                      'index')->name('index')->middleware('hasPermission:business_read');
            Route::get('/create',                'create')->name('create')->middleware('hasPermission:business_create');
            Route::post('/store',                'store')->name('store')->middleware('hasPermission:business_create');
            Route::get('/edit/{id}',             'edit')->name('edit')->middleware('hasPermission:business_update');
            Route::put('/update',                'update')->name('update')->middleware('hasPermission:business_update');
            Route::delete('/delete/{id}',        'destroy')->name('delete')->middleware('hasPermission:business_delete');
            Route::get('/status-update/{id}',    'statusUpdate')->name('status.update')->middleware('hasPermission:business_status_update');
            Route::get('get-all-business',       'getAllBusiness')->name('get.all.business');

            Route::prefix('branch')->controller(BusinessBranchController::class)->name('branch.')->group(function(){
                Route::get('/{id}',                 'index')->name('index')->middleware('hasPermission:business_branch_read');
                Route::get('/create/{id}',           'create')->name('create')->middleware('hasPermission:business_branch_create');
                Route::post('/store',                'store')->name('store')->middleware('hasPermission:business_branch_create');
                Route::get('/edit/{id}',             'edit')->name('edit')->middleware('hasPermission:business_branch_update');
                Route::put('/update',                'update')->name('update')->middleware('hasPermission:business_branch_update');
                Route::get('/status-update/{id}',    'statusUpdate')->name('status.update')->middleware('hasPermission:business_branch_status_update');
                Route::delete('/delete/{id}',        'destroy')->name('delete')->middleware('hasPermission:business_branch_delete');
                Route::get('get-all-branch/{business_id}',    'getAllBranch')->name('get.all.branch');
            });
        });

});
