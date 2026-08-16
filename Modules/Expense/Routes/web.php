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
use Modules\Expense\Http\Controllers\ExpenseController;

Route::middleware(['XSS','IsInstalled'])->group(function(){
    Route::middleware(['auth','isSubscribed'])
        ->group(function(){ 
            //Expense routes
            Route::prefix('accounts/expense')
            ->controller(ExpenseController::class)
            ->name('accounts.expense.')
            ->group(function(){ 
                Route::get('/',                       'index')->name('index')->middleware('hasPermission:expense_read');  
                Route::get('/create',                 'create')->name('create')->middleware('hasPermission:expense_create');
                Route::post('/store',                 'store')->name('store')->middleware('hasPermission:expense_create');
                Route::get('/edit/{id}',              'edit')->name('edit')->middleware('hasPermission:expense_update'); 
                Route::put('/update',                 'update')->name('update')->middleware('hasPermission:expense_update');
                Route::delete('/delete/{id}',         'destroy')->name('delete')->middleware('hasPermission:expense_delete'); 
                Route::post('/branch-account',        'branchAccount')->name('branch.account');  
                Route::get('get-all',                 'getAllExpense')->name('get.all');
             });
            //end Expense routes
        });
    });
  