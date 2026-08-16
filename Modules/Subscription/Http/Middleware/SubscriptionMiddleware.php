<?php

namespace Modules\Subscription\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Modules\Subscription\Entities\Subscription;

class SubscriptionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(env('DEMO')):
            return $next($request); 
        endif;
        if(!isSuperadmin()  ):  
            $subscription = Subscription::where('business_id',business_id())->get()->last(); 
            if($subscription):
                $todayDate       = Carbon::today()->startOfDay()->toDateTimeString();
                $end_date        = Carbon::parse($subscription->end_date)->endOfDay()->toDateTimeString();
                $today_strtotime = strtotime($todayDate);
                $end_strtotime   = strtotime($end_date);
                if($today_strtotime <= $end_strtotime):
                    return $next($request);//subscribed
                else:
                    return redirect()->route('business.subscription.index');//expired
                endif;
            else:
                return redirect()->route('business.subscription.index');//not subscribed
            endif;
        endif; 
        return $next($request); 
    }
}
