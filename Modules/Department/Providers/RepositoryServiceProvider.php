<?php

namespace Modules\Department\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Department\Repositories\DepartmentInterface;
use Modules\Department\Repositories\DepartmentRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(DepartmentInterface::class,      DepartmentRepository::class);
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
