<?php

namespace Modules\Subscription\Http\Controllers;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Subscription\Repositories\Stripe\StripeInterface;

class StripeController extends Controller
{
    protected $stripeRepo;
    public function __construct(StripeInterface $stripeRepo)
    {
        $this->stripeRepo   = $stripeRepo;
    }
    public function StripePayment(Request $request)
    { 
        if(env('DEMO')) {
            Toastr::error(__('payment_system_is_disable_for_the_demo_mode'),__('errors'));
            return response()->json(['success'=>false],400);
        }
        if($this->stripeRepo->stripePayment($request)){
            Toastr::success(__('stripe_payment_successfully'),__('success'));
            return response()->json(['success'=>true],200);
        }else{ 
            return response()->json(['success'=>false],400);
        }
    } 
    
}
