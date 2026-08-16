<?php

namespace Modules\Currency\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Currency\Repositories\CurrencyInterface;
use Modules\Currency\Repositories\CurrencyRepository;

class RepositoryServiceProvicer extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(CurrencyInterface::class,         CurrencyRepository::class);
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
