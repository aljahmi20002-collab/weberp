<?php

namespace Modules\SaleProposal\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\SaleProposal\Repositories\SaleProposalInterface;
use Modules\SaleProposal\Repositories\SaleProposalRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(SaleProposalInterface::class,SaleProposalRepository::class);
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
