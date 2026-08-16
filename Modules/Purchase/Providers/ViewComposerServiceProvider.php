<?php

namespace Modules\Purchase\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Modules\Purchase\Http\ViewComposer\TaxRateComposer;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function boot()
    {
        View::composer('purchase::create',TaxRateComposer::class);
        View::composer('purchase::edit',  TaxRateComposer::class);
        //purchase return
        View::composer('purchase::purchase-return.create',TaxRateComposer::class);
        View::composer('purchase::purchase-return.edit',  TaxRateComposer::class);

        //sale 
        View::composer('sell::sale.create',TaxRateComposer::class);
        View::composer('sell::sale.edit',   TaxRateComposer::class);
    } 
}
