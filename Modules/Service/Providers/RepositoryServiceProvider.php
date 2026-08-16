<?php

namespace Modules\Service\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Service\Repositories\ServiceInterface;
use Modules\Service\Repositories\ServiceRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ServiceInterface::class,  ServiceRepository::class);
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
