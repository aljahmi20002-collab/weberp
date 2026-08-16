<?php

namespace Modules\Subscription\Http\Controllers;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Subscription\Repositories\Skrill\SkrillInterface;

use Obydul\LaraSkrill\SkrillClient;
use Obydul\LaraSkrill\SkrillRequest;
class SkrillController extends Controller
{
    protected $repo;
    private   $skrilRequest;
    public function __construct(SkrillInterface $repo)
    {
        $this->repo = $repo;
         
        // skrill config
        $this->skrilRequest               = new SkrillRequest();
        $this->skrilRequest->pay_to_email = settings('skrill_merchant_email'); // your merchant email 
        $this->skrilRequest->cancel_url   = route('business.subscription.skrill.payment.canceled');
    

    }
    public function skrillMakePayment(Request $request){
        
        $this->skrilRequest->return_url   = route('business.subscription.skrill.payment.success',['planid'=>$request->planid]);

         $this->skrilRequest->logo_url     =  businessLogo();  // optional 
        // create object instance of SkrillRequest
        $this->skrilRequest->amount       =  $request->price;
        $this->skrilRequest->currency     = 'USD';
        $this->skrilRequest->language     = 'EN';
        $this->skrilRequest->prepare_only = '1';

        // custom fields (optional) 
        $this->skrilRequest->site_name      = settings('name');
        $this->skrilRequest->invoice_id     ='Subscribe_inv_'.strtoupper(str()->random(10)); 
        $this->skrilRequest->customer_email = Auth::user()->email;

        // create object instance of SkrillClient
        $client = new SkrillClient($this->skrilRequest);
        $sid    = $client->generateSID(); //return SESSION ID

        // handle error
        $jsonSID      = json_decode($sid);
        if ($jsonSID != null && $jsonSID->code == "BAD_REQUEST"){
            Toastr::error($jsonSID->message,__('errors'));
            return redirect()->back();
        }
        // do the payment
        $redirectUrl = $client->paymentRedirectUrl($sid); //return redirect url
        return redirect()->to($redirectUrl); // redirect user to Skrill payment page
    }

    public function SkrillPaymentSuccess(Request $request)
    {
    
        if($this->repo->SkrillPayment($request)){
            Toastr::success(__('skrill_payment_successfully'),__('success'));
            return redirect()->route('business.subscription.index');
        }else{ 
            Toastr::error(__('error'),__('errors'));
            return redirect()->back();
        }
    }

    public function SkrillPaymentCancel()
    {  
        Toastr::error(__('skrill_payment_canceled'),__('errors'));
        return redirect()->route('business.subscription.index'); 
    }
 
}
