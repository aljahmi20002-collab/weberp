<?php

namespace Modules\Income\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Income\Repositories\IncomeInterface;
use Modules\Income\Repositories\IncomeRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(IncomeInterface::class, IncomeRepository::class);
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
