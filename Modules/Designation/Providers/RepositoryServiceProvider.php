<?php

namespace Modules\Designation\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Designation\Repositories\DesignationInterface;
use Modules\Designation\Repositories\DesignationRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(DesignationInterface::class,    DesignationRepository::class);
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
