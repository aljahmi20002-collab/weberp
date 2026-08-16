<?php

namespace Modules\Warranties\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Warranties\Repositories\WarrantyInterface;
use Modules\Warranties\Repositories\WarrantyRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(WarrantyInterface::class,    WarrantyRepository::class);
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
