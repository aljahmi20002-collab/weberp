<?php

namespace Modules\StockTransfer\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\StockTransfer\Repositories\StockTransferInterface;
use Modules\StockTransfer\Repositories\StockTransferRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(StockTransferInterface::class,    StockTransferRepository::class);
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
