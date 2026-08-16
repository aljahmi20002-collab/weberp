<?php

namespace Modules\SaleProposal\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
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
        //sale 
        View::composer('saleproposal::create',TaxRateComposer::class);
        View::composer('saleproposal::edit',   TaxRateComposer::class);
    }
}
