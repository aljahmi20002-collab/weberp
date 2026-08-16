<?php

namespace Modules\Purchase\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Purchase\Repositories\PurchaseInterface;
use Modules\Purchase\Repositories\PurchaseRepository;
use Modules\Purchase\Repositories\PurchaseReturn\PurchaseReturnInterface;
use Modules\Purchase\Repositories\PurchaseReturn\PurchaseReturnRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(PurchaseInterface::class,       PurchaseRepository::class);
        $this->app->bind(PurchaseReturnInterface::class, PurchaseReturnRepository::class);
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
