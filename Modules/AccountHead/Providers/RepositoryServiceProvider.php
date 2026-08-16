<?php

namespace Modules\AccountHead\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\AccountHead\Repositories\AccountHeadInterface;
use Modules\AccountHead\Repositories\AccountHeadRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(AccountHeadInterface::class,    AccountHeadRepository::class);
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
