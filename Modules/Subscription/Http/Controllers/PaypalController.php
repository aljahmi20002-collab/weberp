<?php

namespace Modules\Subscription\Http\Controllers;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Plan\Repositories\PlanInterface;
use Modules\Subscription\Repositories\Paypal\PaypalInterface;

class PaypalController extends Controller
{
    protected $planRepo,$repo;
    public function __construct(PlanInterface $planRepo,PaypalInterface $repo)
    {
        $this->planRepo   = $planRepo;
        $this->repo       = $repo;
    }
    public function modal(Request $request)
    {
        $plan   = $this->planRepo->getFind($request->plan_id);
        return view('subscription::business.payment_gateway.paypal.modal',compact('plan'));
    }

    public function paypalPayment(Request $request){ 
        if(env('DEMO')) {
            Toastr::error(__('payment_system_is_disable_for_the_demo_mode'),__('errors'));
            return response()->json(['success'=>false],400);
        }
        if($this->repo->PaypalPayment($request)){
            Toastr::success(__('paypal_payment_successfully'),__('success'));
            return response()->json(['success'=>true],200);
        }else{  
            return response()->json(['success'=>false],400);
        } 
    }
     
}
