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
use Modules\Subscription\Http\Controllers\PaypalController;
use Modules\Subscription\Http\Controllers\SkrillController;
use Modules\Subscription\Http\Controllers\StripeController;
use Modules\Subscription\Http\Controllers\SubscriptionController;

Route::middleware(['XSS','IsInstalled'])->group(function(){
    Route::middleware(['auth'])
        ->prefix('subscription')
        ->controller(SubscriptionController::class)
        ->name('subscription.')
        ->group(function(){
            Route::get('/',                      'index')->name('index')->middleware('hasPermission:subscription_read'); 
            Route::get('/subscription-change',   'changeSubscription')->name('change')->middleware('hasPermission:subscription_change'); 
            Route::post('/subscription-changes',  'changeSubscriptionPost')->name('change.post')->middleware('hasPermission:subscription_change'); 
            Route::get('get-all-subscription',     'getAllSubscription')->name('get.all.subscription');
        });

    //business
    Route::middleware(['auth'])
        ->prefix('business-subscription')
        ->controller(SubscriptionController::class)
        ->name('business.subscription.')
        ->group(function(){
            Route::get('/',                           'businessSubscriptionIndex')->name('index');   
            Route::get('/payment-gateway',            'paymentGateway')->name('payment.gateway');   
            //stripe
            Route::post('/stripe-payment',             [StripeController::class,'StripePayment'])->name('stripe.payment');
            //paypal
            Route::get('paypal-modal',                 [PaypalController::class,'modal'])->name('paypal.modal');
            Route::get('paypal-payment',               [PaypalController::class,'paypalPayment'])->name('paypal.payment');
            //skrill
            Route::get('skrill/make-payment',          [SkrillController::class,'skrillMakePayment'])->name('skrill.make.payment');
            Route::get('skrill-payment-success',       [SkrillController::class,'SkrillPaymentSuccess'])->name('skrill.payment.success');
            Route::get('skrill-payment-canceled',      [SkrillController::class,'SkrillPaymentCancel'])->name('skrill.payment.canceled');

        });
});
