<?php
namespace Modules\Subscription\Repositories;

use App\Enums\UserType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Business\Entities\Business;
use Modules\Plan\Entities\Plan;
use Modules\Subscription\Entities\Subscription;
use Modules\Subscription\Repositories\SubscriptionInterface;
class SubscriptionRepository implements SubscriptionInterface{
    public function get(){
        return Subscription::orderBy('id','desc')->get();
    }
    public function subscription($request){//new subscription
        try { 
            DB::beginTransaction();
            $plan                      = Plan::find($request->plan_id);
            $subscription              = new Subscription(); 
            $subscription->business_id = $request->business_id;
            $subscription->plan_id     = $plan->id; 
            $subscription->start_date  = Carbon::today()->format('Y-m-d');
            $subscription->end_date    = Carbon::parse(Carbon::today()->format('Y-m-d'))->addDays($plan->days_count)->format('Y-m-d');
            $subscription->plan_price  = $plan->price; 
            $subscription->paid_via    = $request->paid_via?? 'Custom';
            $subscription->save(); 
            DB::commit();
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            return false;
        }
    }
    public function changeSubscription($request){
        try { 
            $plan  = Plan::find($request->plan_id);
            $subscription              = Subscription::where('business_id', $request->business_id)->first();
            if($subscription):
                $subscription->plan_id     = $plan->id; 
                $subscription->start_date  = Carbon::today()->format('Y-m-d');
                $subscription->end_date    = Carbon::parse(Carbon::today()->format('Y-m-d'))->addDays($plan->days_count)->format('Y-m-d');
                $subscription->plan_price  = $plan->price; 
                $subscription->paid_via    = 'Custom';
                $subscription->save();
              
                $business                     = Business::find($request->business_id);
                $user                         = User::find($business->owner_id);
                $owner = $user;
                $user->permissions            = businessPlanPermission($plan->id);
                $user->save(); 
                $this->employeePermissionsUpdate($owner,$business->id); 

            endif;
            return true;
        } catch (\Throwable $th) { 
            return false;
        }
    }

    public function employeePermissionsUpdate($owner,$business_id){
        $users = User::where('business_id',$business_id)->get();
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