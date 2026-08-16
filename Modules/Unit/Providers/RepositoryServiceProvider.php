<?php

namespace Modules\Unit\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Unit\Repositories\UnitInterface;
use Modules\Unit\Repositories\UnitRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(UnitInterface::class, UnitRepository::class);
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
