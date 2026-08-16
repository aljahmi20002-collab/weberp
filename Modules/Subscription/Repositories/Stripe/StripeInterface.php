<?php
namespace Modules\Subscription\Repositories\Stripe;
interface StripeInterface{ 
    public function StripePayment($request); 
}