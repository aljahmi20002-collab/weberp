<?php
namespace Modules\Subscription\Repositories;
interface SubscriptionInterface{
    public function get();
    public function subscription($request);
    public function changeSubscription($request);
}