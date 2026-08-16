<?php

namespace Modules\ApplyLeave\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\ApplyLeave\Repositories\ApplyLeaveInterface;
use Modules\ApplyLeave\Repositories\ApplyLeaveRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ApplyLeaveInterface::class,         ApplyLeaveRepository::class);
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
