<?php

namespace Modules\Subscription\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Subscription\Repositories\Paypal\PaypalInterface;
use Modules\Subscription\Repositories\Paypal\PaypalRepository;
use Modules\Subscription\Repositories\PlanChange\PlanChangeInterface;
use Modules\Subscription\Repositories\PlanChange\PlanChangeRepository;
use Modules\Subscription\Repositories\Skrill\SkrillInterface;
use Modules\Subscription\Repositories\Skrill\SkrillRepository;
use Modules\Subscription\Repositories\Stripe\StripeInterface;
use Modules\Subscription\Repositories\Stripe\StripeRepository;
use Modules\Subscription\Repositories\SubscriptionInterface;
use Modules\Subscription\Repositories\SubscriptionRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(SubscriptionInterface::class,          SubscriptionRepository::class);
        $this->app->bind(StripeInterface::class,                StripeRepository::class);
        $this->app->bind(PaypalInterface::class,                PaypalRepository::class);
        $this->app->bind(SkrillInterface::class,                SkrillRepository::class);
        $this->app->bind(PlanChangeInterface::class,            PlanChangeRepository::class);
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
