<?php

namespace Modules\Weekend\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Weekend\Repositories\WeekendInterface;
use Modules\Weekend\Repositories\WeekendRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(WeekendInterface::class,           WeekendRepository::class);
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
