<?php

namespace Modules\Account\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Account\Repositories\AccountInterface;
use Modules\Account\Repositories\AccountRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(AccountInterface::class,    AccountRepository::class);
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
