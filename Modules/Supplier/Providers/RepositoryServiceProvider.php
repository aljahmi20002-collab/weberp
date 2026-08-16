<?php

namespace Modules\Supplier\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Supplier\Repositories\SupplierInterface;
use Modules\Supplier\Repositories\SupplierRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(SupplierInterface::class,    SupplierRepository::class);
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
