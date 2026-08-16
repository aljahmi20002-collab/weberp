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
use Modules\BusinessSettings\Http\Controllers\BarcodeSettingsController;
use Modules\BusinessSettings\Http\Controllers\BusinessSettingsController;

Route::middleware(['XSS','IsInstalled'])->group(function(){
    //business settings update
    Route::middleware(['auth'])->group(function(){
        Route::prefix('settings')
            ->controller(BusinessSettingsController::class)
            ->name('settings.')
            ->group(function(){

            Route::prefix('business-settings')
                ->name('business.settings.')
                ->group(function(){
                Route::get('/',                  'index')->name('index');
                Route::get('get-branch',         'getBranch')->name('get.branch');
                Route::get('get-taxrate',        'getTaxRate')->name('get.taxrate');
                Route::get('get-accounthead',    'getAccountHead')->name('get.accounthead');
                Route::get('get-barcodesettings','getbarcodesettings')->name('get.barcodesettings');
                Route::put('/update',  'update')->name('update');
            });

            Route::prefix('barcode-settings')
                ->controller(BarcodeSettingsController::class)
                ->name('barcode.settings.')
                ->group(function(){ 
                    Route::get('/create',           'create')->name('create');
                    Route::post('/store',          'store')->name('store');
                    Route::get('/edit/{id}',        'edit')->name('edit');
                    Route::put('/update',           'update')->name('update');
                    Route::delete('/delete/{id}',   'delete')->name('delete');
                });
        });
 
    });
});
