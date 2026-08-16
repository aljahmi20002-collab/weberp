<?php

namespace Modules\Reports\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Branch\Repositories\BranchInterface;
use Modules\Reports\Transformers\ProfitLossResource;
use Modules\Reports\Repositories\ProfitLossReport\ProfitLossReportInterface;
use Modules\Reports\Transformers\PurchaseReportResource;

class ProfitLossReportController extends Controller
{
    protected $branchRepo,$repo;
    public function __construct(BranchInterface $branchRepo,ProfitLossReportInterface $repo){
        $this->branchRepo      = $branchRepo;
        $this->repo            = $repo;
    }
    public function index(Request $request)
    { 
        $branches     = $this->branchRepo->getAll(business_id());
        return view('reports::profit_loss_reports.index',compact('request','branches'));
    }

    public function getProfit(Request $request){ 
        ini_set('max_execution_time', 900); //900 seconds 
        $data = []; 
        //sales 
        $saleData                            = $this->repo->getSaleData($request);
        $data['total_sales_price']           = $saleData['total_sales_price'];   
        $data['total_sale_tax_amount']       = $saleData['total_tax_amount'];
        $data['total_sale_shipping_charge']  = $saleData['total_shipping_charge'];
        $data['total_sale_discount_amount']  = $saleData['total_discount_amount'];
        //end sales

        //pos 
        $posData                                 = $this->repo->getPosData($request);
        $data['total_pos_sale_price']            = $posData['total_pos_price'];   
        $data['total_pos_sale_tax_amount']       = $posData['total_tax_amount'];
        $data['total_pos_sale_shipping_charge']  = $posData['total_shipping_charge'];
        $data['total_pos_sale_discount_amount']  = $posData['total_discount_amount'];
        //end pos 

        //purchase 
        $purchaseData                           = $this->repo->getPurchaseData($request);
        $data['total_purchase_cost']            = $purchaseData['total_purchase_cost'];
        $data['total_purchase_tax_amount']      = $purchaseData['total_tax_amount'];
        //end purchase  

        //purchaseReturn
        $purchaseReturnData                             = $this->repo->getPurchaseReturnData($request);
        $data['total_purchase_return_price']            = $purchaseReturnData['total_purchase_return_price'];
        $data['total_purchase_return_tax_amount']       = $purchaseReturnData['total_tax_amount'];
        //end purchase Return
        //stock transfer 
        $transfer                                       = $this->repo->getStocktransferData($request);
        $data['total_transfer_price']                   = $transfer['total_transfer_price'];
        $data['total_shipping_charge']                  = $transfer['total_shipping_charge'];

        //income 
        $income                                       = $this->repo->getIncomeData($request);
        $data['total_income']                         = $income['total_income'];
        //expense
        $expense                                      = $this->repo->getExpenseData($request);
        $data['total_expense']                        = $expense['total_expense'];


        //gross profit
        $data['total_gross_profit'] =  (($data['total_sales_price'] + $data['total_pos_sale_price']) - $data['total_purchase_cost']);

        //net profit
        $totaProfitAmount         =  $data['total_gross_profit'] + $data['total_sale_tax_amount'] + $data['total_sale_shipping_charge'] + $data['total_pos_sale_tax_amount'] + $data['total_pos_sale_shipping_charge']; 
        $totalcost                =  $data['total_sale_discount_amount'] + $data['total_pos_sale_discount_amount'] + $data['total_purchase_tax_amount'] + $data['total_shipping_charge'] + $data['total_expense'];
        $data['total_net_profit'] =  ($totaProfitAmount - $totalcost);

        if($request->branch_id && !blank($request->branch_id)):
            $branch                   = $this->branchRepo->getFind($request->branch_id);
            $data['branch']           = @$branch->name; 
        else:
            $data['branch']           = '';
        endif;
        $totalData = new ProfitLossResource((object)$data); 
        return $totalData;
    } 
    
}
