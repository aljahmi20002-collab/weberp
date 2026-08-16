<?php

namespace Modules\Holiday\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Holiday\Repositories\HolidayInterface;
use Modules\Holiday\Repositories\HolidayRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(HolidayInterface::class,             HolidayRepository::class);
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
