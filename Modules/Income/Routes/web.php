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
use Modules\Income\Http\Controllers\IncomeController;

Route::middleware(['XSS','IsInstalled'])->group(function(){
    Route::middleware(['auth','isSubscribed'])
        ->group(function(){ 
            //Income routes
            Route::prefix('accounts/income')
            ->controller(IncomeController::class)
            ->name('accounts.income.')
            ->group(function(){ 
                Route::get('/',                       'index')->name('index')->middleware('hasPermission:income_read');  
                Route::get('/create',                 'create')->name('create')->middleware('hasPermission:income_create');
                Route::post('/store',                 'store')->name('store')->middleware('hasPermission:income_create');
                Route::get('/edit/{id}',              'edit')->name('edit')->middleware('hasPermission:income_update'); 
                Route::put('/update',                 'update')->name('update')->middleware('hasPermission:income_update');
                Route::delete('/delete/{id}',         'destroy')->name('delete')->middleware('hasPermission:income_delete'); 
                Route::post('/branch-account',        'branchAccount')->name('branch.account'); 
                Route::get('get-all',                  'getAllIncome')->name('get.all'); 
             });
            //end Income routes
        });
    });
 