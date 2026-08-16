<?php

namespace Modules\BulkImport\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\BulkImport\Repositories\BulkImportInterface;
use Modules\BulkImport\Repositories\BulkImportRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(BulkImportInterface::class,BulkImportRepository::class);
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
