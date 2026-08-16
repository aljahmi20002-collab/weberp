<?php

namespace Modules\Category\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Category\Repositories\CategoryInterface;
use Modules\Category\Repositories\CategoryRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(CategoryInterface::class,    CategoryRepository::class);
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
