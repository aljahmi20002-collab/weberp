<?php

namespace Modules\Subscription\Http\Middleware;

use App\Traits\ApiReturnFormatTrait;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Modules\Subscription\Entities\Subscription;

class ApiCheckSubscriptionMiddleware
{
    use ApiReturnFormatTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    { 
        if(!isSuperadmin()):  
            $subscription = Subscription::where('business_id',business_id())->first();
            if($subscription):
                $todayDate       = Carbon::today()->startOfDay()->toDateTimeString();
                $end_date        = Carbon::parse($subscription->end_date)->endOfDay()->addSecond(1)->toDateTimeString();
                $today_strtotime = strtotime($todayDate);
                $end_strtotime   = strtotime($end_date);
                if($today_strtotime <= $end_strtotime):
                    return $next($request);//subscribed
                else:
                    return $this->responseWithError('Subscription has been expired.',['isSubscribe'=>false],400);
                endif;
            else:
                return $this->responseWithError('Subscription has been expired.',['isSubscribe'=>false],400);
            endif;
        endif;  
    }
}
