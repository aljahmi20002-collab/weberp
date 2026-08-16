<?php

namespace Modules\Support\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Support\Repositories\AdminSupport\SupportInterface;
use Modules\Support\Repositories\AdminSupport\SupportRepository;
use Modules\Support\Repositories\BusinessSupport\SupportInterface as BusinessSupportSupportInterface;
use Modules\Support\Repositories\BusinessSupport\SupportRepository as BusinessSupportSupportRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(SupportInterface::class, SupportRepository::class);
        $this->app->bind(BusinessSupportSupportInterface::class, BusinessSupportSupportRepository::class);
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
