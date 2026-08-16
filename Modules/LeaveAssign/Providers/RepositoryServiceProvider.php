<?php

namespace Modules\LeaveAssign\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\LeaveAssign\Repositories\LeaveAssignRepository;
use Modules\LeaveAssign\Repositories\LeaveAssignInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(LeaveAssignInterface::class,       LeaveAssignRepository::class);
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
