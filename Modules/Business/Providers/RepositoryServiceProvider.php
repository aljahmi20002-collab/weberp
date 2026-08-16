<?php

namespace Modules\Business\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Business\Repositories\BusinessInterface;
use Modules\Business\Repositories\BusinessRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(BusinessInterface::class,     BusinessRepository::class);
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
