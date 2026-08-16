<?php

namespace Modules\AssetCategory\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\AssetCategory\Repositories\AssetCategoryInterface;
use Modules\AssetCategory\Repositories\AssetCategoryRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(AssetCategoryInterface::class,   AssetCategoryRepository::class);
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
