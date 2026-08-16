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
use Modules\SaleProposal\Http\Controllers\SaleProposalController;

Route::middleware(['XSS','IsInstalled'])->group(function(){
    Route::middleware(['auth','isSubscribed'])->group(function(){
        Route::prefix('proposal')
        ->controller(SaleProposalController::class)
        ->name('saleproposal.')
        ->group(function() {

            Route::get('/',                      'index')->name('index')->middleware('hasPermission:sale_proposal_read'); 
            Route::get('get-all-sale-proposal',  'getAllSale')->name('get.all')->middleware('hasPermission:sale_proposal_read'); 
            Route::get('/create',                 'create')->name('create')->middleware('hasPermission:sale_create');
            Route::post('/store',                 'store')->name('store')->middleware('hasPermission:sale_create');
            Route::get('/edit/{id}',              'edit')->name('edit')->middleware('hasPermission:sale_proposal_update'); 
            Route::put('/update',                 'update')->name('update')->middleware('hasPermission:sale_proposal_update');
            Route::delete('/delete/{id}',         'destroy')->name('delete')->middleware('hasPermission:sale_proposal_delete'); 
            Route::get('details/{id}',            'details')->name('details')->middleware('hasPermission:sale_proposal_read'); 

            Route::post('variation/location/find','VariationLocationFind')->name('variation.location.find');
            Route::post('variation-location-item','VariationLocationItem')->name('variation.location.item');
            Route::post('get-taxrate',            'getTaxrate')->name('taxrate.get');

            Route::get('manage/payment/{id}',     'managePayment')->name('manage.payment')->middleware('hasPermission:sale_proposal_read_payment');  
            Route::post('add/payment',            'addPayment')->name('add.payment')->middleware('hasPermission:sale_proposal_add_payment');  
            Route::get('edit/payment/{id}',       'editPayment')->name('edit.payment')->middleware('hasPermission:sale_proposal_update_payment');  
            Route::post('update/payment',         'updatePayment')->name('update.payment')->middleware('hasPermission:sale_proposal_update_payment');  
            Route::get('delete/payment/{id}',     'deletePayment')->name('delete.payment')->middleware('hasPermission:sale_proposal_delete_payment'); 
            
            Route::get('invoice/view/{id}',        'view')->name('invoice.view');

        });
    });
});
 