<?php

namespace Modules\DutySchedule\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\DutySchedule\Repositories\DutyScheduleInterface;
use Modules\DutySchedule\Repositories\DutyScheduleRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(DutyScheduleInterface::class,       DutyScheduleRepository::class);
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
