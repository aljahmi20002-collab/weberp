<?php

namespace Modules\Plan\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Plan\Repositories\PlanInterface;
use Modules\Plan\Repositories\PlanRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(PlanInterface::class,               PlanRepository::class);
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
