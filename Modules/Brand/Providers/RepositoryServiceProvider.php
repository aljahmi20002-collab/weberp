<?php

namespace Modules\Brand\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Brand\Repositories\BrandInterface;
use Modules\Brand\Repositories\BrandRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(BrandInterface::class, BrandRepository::class);
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
