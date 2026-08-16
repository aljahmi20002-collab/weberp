<?php

namespace Modules\Assets\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Assets\Repositories\AssetInterface;
use Modules\Assets\Repositories\AssetRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(AssetInterface::class, AssetRepository::class);
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
