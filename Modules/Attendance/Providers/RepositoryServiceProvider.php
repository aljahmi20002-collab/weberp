<?php

namespace Modules\Attendance\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Attendance\Repositories\AttendanceInterface;
use Modules\Attendance\Repositories\AttendanceRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(AttendanceInterface::class, AttendanceRepository::class);
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
