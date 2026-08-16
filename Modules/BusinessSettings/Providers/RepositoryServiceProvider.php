<?php

namespace Modules\BusinessSettings\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\BusinessSettings\Repositories\BarcodeSettings\BarcodeSettingsInterface;
use Modules\BusinessSettings\Repositories\BarcodeSettings\BarcodeSettingsRepository;
use Modules\BusinessSettings\Repositories\BusinessSettingsInterface;
use Modules\BusinessSettings\Repositories\BusinessSettingsRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(BusinessSettingsInterface::class,  BusinessSettingsRepository::class);
        $this->app->bind(BarcodeSettingsInterface::class,  BarcodeSettingsRepository::class);
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
