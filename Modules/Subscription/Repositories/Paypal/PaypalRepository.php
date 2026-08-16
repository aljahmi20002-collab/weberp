<?php
namespace Modules\Subscription\Repositories\Paypal;

use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Modules\Business\Entities\Business;
use Modules\Plan\Repositories\PlanInterface;
use Modules\Subscription\Entities\Subscription;
use Modules\Subscription\Repositories\Paypal\PaypalInterface;

class PaypalRepository implements PaypalInterface{
    protected $planRepo;
    public function __construct(PlanInterface $planRepo){
        $this->planRepo = $planRepo;
    }   
    public function PaypalPayment($request){  
        try {   
            $plan                      = $this->planRepo->getFind($request->planid); 
            $subscription              = Subscription::where('business_id',Auth::user()->business->id)->first();
            $subscription->plan_id     = $plan->id;
            $subscription->start_date  = Carbon::today()->format('Y-m-d');
            $subscription->end_date  = Carbon::parse(Carbon::today()->format('Y-m-d'))->addDays($plan->days_count)->format('Y-m-d');
            $subscription->plan_price  = $plan->price; 
            $subscription->paid_via    = 'Paypal'; 
            $subscription->save();

            $business                     = Business::find(Auth::user()->business->id);
            $user                         = User::find($business->owner_id);
            $owner = $user;
            $user->permissions            = businessPlanPermission($plan->id);
            $user->save();  
            $this->employeePermissionsUpdate($owner);  
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