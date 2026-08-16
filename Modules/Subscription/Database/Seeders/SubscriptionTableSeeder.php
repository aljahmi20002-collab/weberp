<?php

namespace Modules\Subscription\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Business\Entities\Business;
use Modules\Plan\Entities\Plan;
use Modules\Plan\Enums\IsDefault;
use Modules\Subscription\Entities\Subscription;

class SubscriptionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $businesses = Business::all();
        $plan       = Plan::where('is_default',IsDefault::YES)->first();
        foreach ($businesses as $business) {
            $subscription              = new Subscription();
            $subscription->plan_id     = $plan->id;
            $subscription->business_id = $business->id;
            $subscription->start_date  = Carbon::today()->format('Y-m-d');
            $subscription->end_date    = Carbon::parse(Carbon::today()->format('Y-m-d'))->addDays($plan->days_count)->format('Y-m-d');
            $subscription->plan_price  = $plan->price; 
            $subscription->paid_via    = 'Stripe';
            $subscription->save();
         }         
    }
}
