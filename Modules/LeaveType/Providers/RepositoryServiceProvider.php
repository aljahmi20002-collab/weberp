<?php

namespace Modules\LeaveType\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\LeaveType\Repositories\LeaveTypeInterface;
use Modules\LeaveType\Repositories\LeaveTypeRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(LeaveTypeInterface::class,      LeaveTypeRepository::class);
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
