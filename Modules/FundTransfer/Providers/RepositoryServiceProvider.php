<?php

namespace Modules\FundTransfer\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\FundTransfer\Repositories\FundTransferInterface;
use Modules\FundTransfer\Repositories\FundTransferRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(FundTransferInterface::class,        FundTransferRepository::class);
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
