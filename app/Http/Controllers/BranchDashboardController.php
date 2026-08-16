<?php

namespace App\Http\Controllers;

use App\Repositories\BranchDashboard\BranchDashboardInterface;
use App\Traits\ApiReturnFormatTrait;
use Illuminate\Http\Request;

class BranchDashboardController extends Controller
{
    use ApiReturnFormatTrait;
    public $data =[];
    protected $repo;
    public function __construct(BranchDashboardInterface $repo)
    {
        $this->repo = $repo;
    }
    public function TotalSales(Request $request){
        if($request->ajax()):  
            //sales
            $this->data['total_sales']     = number_format($this->repo->totalSales($request)['total_sales_amount'],2);
            $this->data['total_payment']   = number_format($this->repo->totalSales($request)['total_payments'],2);
            $this->data['total_due']       = number_format($this->repo->totalSales($request)['total_due'],2);
            $this->data['total_net']       = number_format($this->repo->totalSales($request)['total_net'],2);
             
            //other
            $this->data['total_expense']   = number_format($this->repo->totalSales($request)['total_expense'],2);

            return $this->responseWithSuccess(__('successfully_data_found'),$this->data);
        endif;
        return $this->responseWithError(__('error'));
    }
    public function TotalPos(Request $request){
        if($request->ajax()):   
            //pos 
            $this->data['total_pos']          = number_format($this->repo->totalPos($request)['total_pos_amount'],2);
            $this->data['total_pos_payments'] = number_format($this->repo->totalPos($request)['total_pos_payments'],2);
            $this->data['total_pos_due']      = number_format($this->repo->totalPos($request)['total_pos_due'],2); 
            //other
            $this->data['total_expense']      = number_format($this->repo->totalSales($request)['total_expense'],2);

            return $this->responseWithSuccess(__('successfully_data_found'),$this->data);
        endif;
        return $this->responseWithError(__('error'));
    }

    public function TotalPurchase (Request $request){
        if($request->ajax()):  
            //purchase   
            $this->data['total_purchase_items']    = $this->repo->totalPurchase($request)['total_purchase_items'];
            $this->data['total_purchase']          = number_format($this->repo->totalPurchase($request)['total_purchase_amount'],2);
            $this->data['total_purchase_payments'] = number_format($this->repo->totalPurchase($request)['total_purchase_payments'],2);
            $this->data['total_purchase_due']      = number_format($this->repo->totalPurchase($request)['total_purchase_due'],2); 
            return $this->responseWithSuccess(__('successfully_data_found'),$this->data);
        endif;
        return $this->responseWithError(__('error'));
    }

    public function TotalPurchaseReturn(Request $request){
        if($request->ajax()):  
            //purchase   
            $this->data['total_purchase_return_items']    = $this->repo->totalPurchaseReturn($request)['total_purchase_return_items'];
            $this->data['total_purchase_return']          = number_format($this->repo->totalPurchaseReturn($request)['total_purchase_return_amount'],2);
            $this->data['total_purchase_return_payments'] = number_format($this->repo->totalPurchaseReturn($request)['total_purchase_return_payments'],2);
            $this->data['total_purchase_return_due']      = number_format($this->repo->totalPurchaseReturn($request)['total_purchase_return_due'],2); 
            return $this->responseWithSuccess(__('successfully_data_found'),$this->data);
        endif;
        return $this->responseWithError(__('error'));
    }

    //recent sales
    public function RecentSales(Request $request){
        if($request->ajax()):
            $sales         = $this->repo->recentSales($request);
            return view('backend.branch_dashboard.recent_sales',compact('sales'))->render();
        endif;
        return '';
    }
    //recent pos
    public function RecentPos(Request $request){
        if($request->ajax()):
            $poses         = $this->repo->recentPos($request);
            return view('pos::branch_dashboard.recent_pos',compact('poses'))->render();
        endif;
        return '';
    }
}
