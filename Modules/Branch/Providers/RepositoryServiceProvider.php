<?php

namespace Modules\Branch\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Branch\Repositories\BranchInterface;
use Modules\Branch\Repositories\BranchRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(BranchInterface::class,         BranchRepository::class);
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
