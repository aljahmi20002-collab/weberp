<?php

namespace Modules\Expense\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Expense\Repositories\ExpenseInterface;
use Modules\Expense\Repositories\ExpenseRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ExpenseInterface::class,   ExpenseRepository::class);
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
