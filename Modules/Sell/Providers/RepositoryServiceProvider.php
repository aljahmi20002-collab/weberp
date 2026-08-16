<?php

namespace Modules\Sell\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Sell\Repositories\SaleInterface;
use Modules\Sell\Repositories\SaleRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(SaleInterface::class,   SaleRepository::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
