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
use Modules\Currency\Http\Controllers\CurrencyController;

Route::middleware(['XSS','IsInstalled'])->group(function(){
    Route::middleware(['auth'])
        ->prefix('settings/currency')
        ->controller(CurrencyController::class)
        ->name('settings.currency.')
        ->group(function(){
            Route::get('/',                      'index')->name('index')->middleware('hasPermission:currency_read');
            Route::get('/create',                'create')->name('create')->middleware('hasPermission:currency_create');
            Route::post('/store',                'store')->name('store')->middleware('hasPermission:currency_create');
            Route::get('/edit/{id}',             'edit')->name('edit')->middleware('hasPermission:currency_update');
            Route::put('/update',                'update')->name('update')->middleware('hasPermission:currency_update');
            Route::delete('/delete/{id}',        'destroy')->name('delete')->middleware('hasPermission:currency_delete');
            Route::get('/status-update/{id}',    'statusUpdate')->name('status.update')->middleware('hasPermission:currency_status_update');
            Route::get('get-all',                 'getAllCurrencies')->name('get.all');
        });
});
