<?php
namespace Modules\Subscription\Repositories\Stripe;

use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Modules\Business\Entities\Business;
use Modules\Plan\Repositories\PlanInterface;
use Modules\Subscription\Entities\Subscription;
use Modules\Subscription\Repositories\Stripe\StripeInterface;
class StripeRepository implements StripeInterface{
    protected $planRepo;
    public function __construct(PlanInterface $planRepo){
        $this->planRepo = $planRepo;
    }   
    public function StripePayment($request){ 
  
        try {  
            \Stripe\Stripe::setApiKey(settings('stripe_secret_key'));
            $plan     = $this->planRepo->getFind($request->planid);
       
            if($plan->price > 0):
                $customer = \Stripe\Customer::create([
                        "address" => [ 
                                "country"     => @Auth::user()->business->currency->country,
                            ],
                        "email"  => @Auth::user()->email,
                        "name"   => @Auth::user()->business->business_name,
                        "source" => @$request->tokenId
                    ]);

               
                $charges = \Stripe\Charge::create ([
                    "amount"      => $request->amount * 100,
                    "currency"    => "usd",
                    "customer"    => $customer->id,
                    "description" => settings('name').' '.$plan->name.' plan '.'subscribed.',
                    "shipping" => [
                        "name"    => @Auth::user()->business->business_name,
                        "address" => [ 
                            "country"     =>  @Auth::user()->business->currency->country
                        ],
                    ]
                ]);
               
                
            else:
                $customer = true;
                $charges  = true;
            endif; 
            if($customer && $charges):
                $subscription              = Subscription::where('business_id',Auth::user()->business->id)->first();
                $subscription->plan_id     = $plan->id;
                $subscription->start_date  = Carbon::today()->format('Y-m-d');
                $subscription->end_date  = Carbon::parse(Carbon::today()->format('Y-m-d'))->addDays($plan->days_count)->format('Y-m-d');
                $subscription->plan_price  = $plan->price; 
                $subscription->paid_via    = 'Stripe';
                $subscription->save();

                $business                     = Business::find(Auth::user()->business->id);
                $user                         = User::find($business->owner_id);
                $owner = $user;
                $user->permissions            = businessPlanPermission($plan->id);
                $user->save(); 
                $this->employeePermissionsUpdate($owner);
            else:
                return false;
            endif;
            return true;
        }catch(Exception $th){ 
            return false;
        }
    }
 
    public function employeePermissionsUpdate($owner){
        $users = User::where('business_id',Auth::user()->business->id)->get();
        $permissions = [];
        foreach ($users as $user) {
           if(!blank($user->permissions)):
              foreach ($user->permissions as $permission) {
                 if(in_array($permission,$owner->permissions)):
                    $permissions[] = $permission;
                 endif;
              }
           endif;
           $user->permissions   = $permissions;
           $user->save();
        }
    }
}