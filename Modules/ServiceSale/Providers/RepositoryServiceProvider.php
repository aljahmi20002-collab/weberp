<?php

namespace Modules\ServiceSale\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\ServiceSale\Repositories\ServiceSaleInterface;
use Modules\ServiceSale\Repositories\ServiceSaleRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ServiceSaleInterface::class,     ServiceSaleRepository::class);
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
