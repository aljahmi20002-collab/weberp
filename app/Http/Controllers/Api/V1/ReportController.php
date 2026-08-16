<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\UserResource;
use App\Traits\ApiReturnFormatTrait;
use Illuminate\Http\Request;
use App\Repositories\User\UserInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Modules\Branch\Http\Resources\v1\BranchResource;
use Modules\Branch\Repositories\BranchInterface;
use Modules\Brand\Http\Resources\v1\BrandResource;
use Modules\Brand\Repositories\BrandInterface;
use Modules\Category\Http\Resources\v1\CategoryResource;
use Modules\Category\Repositories\CategoryInterface;
use Modules\Pos\Repositories\PosReport\PosReportInterface;
use Modules\Reports\Http\Requests\AttendanceReportRequest;
use Modules\Reports\Http\Resources\v1\AttendanceReportResource;
use Modules\Reports\Http\Resources\v1\StockReportResource;
use Modules\Reports\Repositories\AttendanceReport\AttendanceReportInterface;
use Modules\Reports\Repositories\CustomerReport\CustomerReportInterface;
use Modules\Reports\Repositories\ExpenseReport\ExpenseReportInterface;
use Modules\Reports\Repositories\ProductWiseProfit\ProductWiseProfitInterface;
use Modules\Reports\Repositories\ProfitLossReport\ProfitLossReportInterface;
use Modules\Reports\Repositories\PurchaseReport\PurchaseReportInterface;
use Modules\Reports\Repositories\SaleReport\SaleReportInterface;
use Modules\Reports\Repositories\StockReport\StockReportInterface;
use Modules\Reports\Transformers\ProfitLossResource;
use Modules\Sell\Repositories\SaleInterface;
use Modules\ServiceSale\Repositories\ServiceSaleInterface;
use Modules\Unit\Http\Resources\v1\UnitResource;
use Modules\Unit\Repositories\UnitInterface;

class ReportController extends Controller
{
    use ApiReturnFormatTrait;
   
    protected $repo,
        $userRepo,
        $profitLossRepo,
        $branchRepo,
        $productWiseProfitRepo,
        $expenseRepo,
        $stockRepo,
        $customerReportRepo,
        $posReportRepo,
        $purchaseReportRepo,
        $brandRepo,
        $unitRepo,
        $categoryRepo,
        $saleReportRepo,
        $serviceSaleRepo;
    public function __construct(
        AttendanceReportInterface $repo,
        UserInterface $userRepo,
        ProfitLossReportInterface $profitLossRepo,
        BranchInterface $branchRepo,
        ProductWiseProfitInterface $productWiseProfitRepo,
        ExpenseReportInterface $expenseRepo,
        StockReportInterface $stockRepo,
        CustomerReportInterface $customerReportRepo,
        PosReportInterface $posReportRepo,
        PurchaseReportInterface $purchaseReportRepo,

        BrandInterface $brandRepo,
        UnitInterface $unitRepo,
        CategoryInterface $categoryRepo,
        SaleReportInterface    $saleReportRepo,
        ServiceSaleInterface   $serviceSaleRepo
        
    ) {
        $this->repo           = $repo;
        $this->userRepo       = $userRepo;
        $this->profitLossRepo = $profitLossRepo;
        $this->branchRepo     = $branchRepo;
        $this->productWiseProfitRepo = $productWiseProfitRepo;
        $this->expenseRepo    = $expenseRepo;
        $this->stockRepo      = $stockRepo;
        $this->customerReportRepo  = $customerReportRepo;
        $this->posReportRepo       = $posReportRepo;
        $this->purchaseReportRepo  = $purchaseReportRepo;
        $this->brandRepo           = $brandRepo;
        $this->unitRepo            = $unitRepo;
        $this->categoryRepo        = $categoryRepo;
        $this->saleReportRepo      = $saleReportRepo;
        $this->serviceSaleRepo     = $serviceSaleRepo;
    }

    public function employees()
    {
        $employee_list   = $this->userRepo->getReportsUsers();
        return $this->responseWithSuccess(__('success'), [
            'employee_list' =>UserResource::collection($employee_list)
        ], 200);
    }

    public function attendanceReport(AttendanceReportRequest $request)
    {
        $request->validate([
            'date' => 'required',
            'employee_id' => 'required'
        ]);

        $employee    = $this->userRepo->edit($request->employee_id);
        $request['employee'] =  $employee;
        $attendance_reports = new AttendanceReportResource($this->repo->getReport($request));
        return $this->responseWithSuccess(__('success'), [
            'attendance_reports' => $attendance_reports
        ], 200);
    }

    public function profitLossReport(Request $request)
    {
        $request->validate([
            'date' => 'required',
            'branch_id' => 'required'
        ]);

        ini_set('max_execution_time', 900); //900 seconds
        $data = [];
        //sales
        $saleData                            = $this->profitLossRepo->getSaleData($request);
        $data['total_sales_price']           = $saleData['total_sales_price'];
        $data['total_sale_tax_amount']       = $saleData['total_tax_amount'];
        $data['total_sale_shipping_charge']  = $saleData['total_shipping_charge'];
        $data['total_sale_discount_amount']  = $saleData['total_discount_amount'];
        //end sales

        //pos
        $posData                                 = $this->profitLossRepo->getPosData($request);
        $data['total_pos_sale_price']            = $posData['total_pos_price'];
        $data['total_pos_sale_tax_amount']       = $posData['total_tax_amount'];
        $data['total_pos_sale_shipping_charge']  = $posData['total_shipping_charge'];
        $data['total_pos_sale_discount_amount']  = $posData['total_discount_amount'];
        //end pos

        //purchase
        $purchaseData                           = $this->profitLossRepo->getPurchaseData($request);
        $data['total_purchase_cost']            = $purchaseData['total_purchase_cost'];
        $data['total_purchase_tax_amount']      = $purchaseData['total_tax_amount'];
        //end purchase

        //purchaseReturn
        $purchaseReturnData                             = $this->profitLossRepo->getPurchaseReturnData($request);
        $data['total_purchase_return_price']            = $purchaseReturnData['total_purchase_return_price'];
        $data['total_purchase_return_tax_amount']       = $purchaseReturnData['total_tax_amount'];
        //end purchase Return
        //stock transfer
        $transfer                                       = $this->profitLossRepo->getStocktransferData($request);
        $data['total_transfer_price']                   = $transfer['total_transfer_price'];
        $data['total_shipping_charge']                  = $transfer['total_shipping_charge'];

        //income
        $income                                       = $this->profitLossRepo->getIncomeData($request);
        $data['total_income']                         = $income['total_income'];
        //expense
        $expense                                      = $this->profitLossRepo->getExpenseData($request);
        $data['total_expense']                        = $expense['total_expense'];


        //gross profit
        $data['total_gross_profit'] =  (($data['total_sales_price'] + $data['total_pos_sale_price']) - $data['total_purchase_cost']);

        //net profit
        $totaProfitAmount    = $data['total_gross_profit'] + $data['total_sale_tax_amount'] + $data['total_sale_shipping_charge'] + $data['total_pos_sale_tax_amount'] + $data['total_pos_sale_shipping_charge'];
        $totalcost    = $data['total_sale_discount_amount'] + $data['total_pos_sale_discount_amount'] + $data['total_purchase_tax_amount'] + $data['total_shipping_charge'] + $data['total_expense'];
        $data['total_net_profit'] =  ($totaProfitAmount - $totalcost);

        if ($request->branch_id && !blank($request->branch_id)) :
            $branch                   = $this->branchRepo->getFind($request->branch_id);
            $data['branch']           = @$branch->name;
        else :
            $data['branch']           = '';
        endif;
        $totalData = new ProfitLossResource((object)$data);
        return $this->responseWithSuccess(__('success'), [
            'profit_loss_report' => $totalData
        ], 200);
    }

    public function productSaleProfitReport(Request $request)
    {
        $request->validate([
            'date' => 'required',
            'branch_id' => 'required'
        ]);

        $request['date']  =  Carbon::today()->format('m/d/Y') . ' - ' . Carbon::today()->format('m/d/Y');
        $branches     =  $this->branchRepo->getAll(business_id());
        $products     =  $this->productWiseProfitRepo->getProfit($request);
        return $this->responseWithSuccess(__('success'), [
            'request' => $request,
            'branches' => $branches,
            'products' => $products,
        ], 200);
    }

    public function expenseReport(Request $request)
    {
        $request->validate([
            'date' => 'required',
            'branch_id' => 'required'
        ]);

        if ($request->branch_id && !blank($request->branch_id)) :
            $branch                   = $this->branchRepo->getFind($request->branch_id);
            $branch_name               = @$branch->name;
        else :
            $branch_name = '';
        endif;
        $expenses = $this->expenseRepo->getReport($request);
        return $this->responseWithSuccess(__('success'), [
            'request' => $request,
            'branch_name' => $branch_name,
            'expenses' => $expenses
        ], 200);
    }

    function resource_collection($resource): array
    {
        return json_decode($resource->response()->getContent(), true) ?? [];
    }


    public function stockReportPage(){
    
        $branches         =  $this->branchRepo->getAll(business_id()); 
        $brands           =  $this->brandRepo->getBrands(business_id()); 
        $units            =  $this->unitRepo->getUnits(business_id());
        $categories       =  $this->categoryRepo->getActiveCategory(business_id());
  
        return $this->responseWithSuccess('Stock report create data',[
            'branches'      => BranchResource::collection($branches),
            'brands'        => BrandResource::collection($brands), 
            'units'         => UnitResource::collection($units),
            'categories'    => CategoryResource::collection($categories)
        ],200);

    }
    public function stockReport(Request $request)
    {
        $request->validate([
            // 'branch_id' => 'required',
            // 'category_id' => 'required',
            // 'subcategory_id' => 'required',
            // 'brand_id' => 'required',
            // 'unit_id' => 'required',
        ]);

        if ($request->branch_id && !blank($request->branch_id)) :
            $branch                   = $this->branchRepo->getFind($request->branch_id);
            $data['branch']           = @$branch->name;
        else :
            $data['branch']           = '';
        endif;
        $data['products'] = $this->stockRepo->getReport($request);
        $data['request']  =  $request->all();
        $data['total_calculation'] = $this->stockRepo->getTotalCalculation($data['products']);
        $data['products'] = $this->resource_collection(StockReportResource::collection($data['products']));
        return $this->responseWithSuccess(__('success'), $data, 200);
    }

    public function customerSaleReport(Request $request)
    {
        $request->validate([
            'date'        => 'required',
            'branch_id'   => 'required',
            'customer_id' => 'required',
        ]);

        ini_set('max_execution_time', 900); //900 seconds
        $get_total           = $this->customerReportRepo->getReport($request);
        $sales               = $this->customerReportRepo->getSales($request);
        $customer_name       = $this->customerReportRepo->getCustomerName($request);
        $branch_name         = $this->customerReportRepo->getBranchName($request);
        return $this->responseWithSuccess(__('success'), [
            'get_total' => $get_total,
            'sales' => $sales,
            'customer_name' => $customer_name,
            'branch_name' => $branch_name,
        ], 200);
    }

    public function customerPosReport(Request $request)
    {
        $request->validate([
            'date' => 'required',
            'branch_id' => 'required',
            'customer_id' => 'required',
        ]);

        $poses = $this->posReportRepo->getReport($request);
        if ($request->branch_id && !blank($request->branch_id)) :
            $branch                   = $this->branchRepo->getFind($request->branch_id);
            $branch_name               = @$branch->name;
        else :
            $branch_name           = '';
        endif;
        return $this->responseWithSuccess(__('success'), [
            'poses' => $poses,
            'request' => $request,
            'branch_name' => $branch_name,
        ], 200);
    }

    public function purchaseReport(Request $request)
    {
        $request->validate([
            'date' => 'required',
            'branch_id' => 'required',
        ]);

        $purchases = $this->purchaseReportRepo->getReport($request);
        if ($request->branch_id && !blank($request->branch_id)) :
            $branch                   = $this->branchRepo->getFind($request->branch_id);
            $branch_name               = @$branch->name;
        else :
            $branch_name           = '';
        endif;
        return $this->responseWithSuccess(__('success'), [
            'purchases' => $purchases,
            'request' => $request,
            'branch_name' => $branch_name,
        ], 200);
    }

 
    public function SaleReports(Request $request){
        $request->validate([
            'date' => 'required', 
        ]);

        $data['sales'] = $this->saleReportRepo->getReport($request);
        if($request->branch_id && !blank($request->branch_id)):
            $branch               = $this->branchRepo->getFind($request->branch_id);
            $branchname           = @$branch->name;
        elseif(isUser()):
            $branchname           = Auth::user()->branch->name;
        else:
            $branchname           = __('all');
        endif;
        $data['branch_name'] = $branchname;
        $data['request']     = $request->all();
        $data['total']       = [
            'total_sale_price' => @businessCurrency(business_id()).$data['sales']->sum('total_sale_price'),
            'total_payments'   => @businessCurrency(business_id()).$data['sales']->sum('TotalPaid'),
            'total_due'        => @businessCurrency(business_id()).$data['sales']->sum('TotalDueAmount')
        ];
        return $this->responseWithSuccess('Sale report list',$data,200);
    }
 
    public function serviceSaleReports(Request $request){
        $request->validate([
            'date' => 'required', 
        ]);

        $data['service_sales'] = $this->serviceSaleRepo->getReport($request);
        if($request->branch_id && !blank($request->branch_id)):
             $branch                   = $this->branchRepo->getFind($request->branch_id);
             $branchname               = @$branch->name;
         elseif(isUser()):
             $branchname           = Auth::user()->branch->name;
         else:
             $branchname           = __('all');
         endif; 
        $data['branch_name'] = $branchname;
        $data['request']     = $request->all();
        $data['total']       = [
            'total_sale_price' => @businessCurrency(business_id()).$data['service_sales']->sum('total_sale_price'),
            'total_payments'   => @businessCurrency(business_id()).$data['service_sales']->sum('TotalPaid'),
            'total_due'        => @businessCurrency(business_id()).$data['service_sales']->sum('TotalDueAmount')
        ];
        return $this->responseWithSuccess('Service Sale report list',$data,200);
    }
}
