<?php

namespace Modules\Subscription\Http\Controllers;

use App\Models\Backend\Role;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Business\Repositories\BusinessInterface;
use Modules\Currency\Repositories\CurrencyInterface;
use Modules\Plan\Repositories\PlanInterface;
use Modules\Subscription\Http\Requests\PlanchangeRequest;
use Modules\Subscription\Repositories\SubscriptionInterface;
use Yajra\DataTables\DataTables;

class SubscriptionController extends Controller
{ 
    protected $repo,$businessRepo,$planRepo,$currencyRepo;
    public function __construct(
        SubscriptionInterface $repo,
        BusinessInterface $businessRepo,
        PlanInterface $planRepo,
        CurrencyInterface $currencyRepo
    ){
        $this->repo         = $repo;
        $this->businessRepo = $businessRepo;
        $this->planRepo     = $planRepo;
        $this->currencyRepo = $currencyRepo;
    }
    public function index()
    {
        
        return view('subscription::index');
    }
    public function getAllSubscription(){
        $subscriptions = $this->repo->get();
        return DataTables::of($subscriptions)
        ->addIndexColumn()  
        ->editColumn('business',function($subscription){
            return @$subscription->business->business_name;
        })
        ->editColumn('plan',function($subscription){
            return @$subscription->plan->name;
        })
        ->editColumn('start_date',function($subscription){
            return  dateFormat2($subscription->start_date);
        })
        ->editColumn('end_date',function($subscription){
            return dateFormat2($subscription->end_date);
        })
        ->editColumn('price',function($subscription){
            return systemCurrency().' '. @$subscription->plan_price;
        })
        ->editColumn('paid_via',function($subscription){
            return  @$subscription->paid_via;
        }) 
        ->rawColumns(['business','plan','start_date','end_date','price','paid_via'])
        ->make(true);
    }
 
    public function changeSubscription(){
        $businesses    = $this->businessRepo->getAll();
        $plans         = $this->planRepo->getActive(); 
        return view('subscription::change_subscription',compact('businesses','plans'));
    }
    public function changeSubscriptionPost(PlanchangeRequest $request)
    { 
        if($this->repo->changeSubscription($request)){
            Toastr::success(__('subscription_changes_successfully'),__('success'));
            return redirect()->route('subscription.index');
        }else{
            Toastr::error(__('error'),__('errors'));
            return redirect()->back();
        }
    }
 
    //business subscription
    public function businessSubscriptionIndex(){   
        $plans     = $this->planRepo->getActive();
        $currency  = $this->currencyRepo->getSymbolWiseFind(settings('currency')); 
        return view('subscription::business.index',compact('plans','currency'));
    }

    public function paymentGateway(Request $request){
        $plan      = $this->planRepo->getFind($request->plan_id);  
        return view('subscription::business.payment_gateway.gateway_modal',compact('plan'));
    }
  
}
