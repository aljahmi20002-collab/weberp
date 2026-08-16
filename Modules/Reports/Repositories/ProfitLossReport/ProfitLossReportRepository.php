<?php
namespace Modules\Reports\Repositories\ProfitLossReport;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Modules\Expense\Entities\Expense;
use Modules\Income\Entities\Income;
use Modules\Pos\Entities\Pos;
use Modules\Purchase\Entities\Purchase;
use Modules\Purchase\Entities\PurchaseReturn;
use Modules\Sell\Entities\Sale;
use Modules\Reports\Repositories\ProfitLossReport\ProfitLossReportInterface;
use Modules\StockTransfer\Entities\StockTransfer;

class ProfitLossReportRepository implements ProfitLossReportInterface{
    public function getSaleData($request){  
        $date = explode(' - ', $request->date);
        if(is_array($date)) {
            $from   = Carbon::parse(trim($date[0]))->startOfDay()->toDateTimeString();
            $to     = Carbon::parse(trim($date[1]))->endOfDay()->toDateTimeString(); 
        }  
        $sales      = Sale::with(['saleItems'])->where(function($query)use($request){
                                $query->where('business_id',business_id());
                                if(isUser()):
                                    $query->where('branch_id',Auth::user()->branch_id);
                                elseif(business() && !blank($request->branch_id)):
                                    $query->where('branch_id',$request->branch_id);
                                endif;
                        })->whereBetween('updated_at',[$from,$to])->get();
        $totalSalesPrice       = 0; 
        $totalTaxAmount        = 0; 
        $totalShippingCharge   = 0; 
        $totalDiscountAmount   = 0; 
        foreach ($sales as  $sale) {  
            $totalSalesPrice       += $sale->saleItems->sum('total_unit_price');
            $totalTaxAmount        += $sale->total_tax_amount;
            $totalShippingCharge   += $sale->shipping_charge;
            $totalDiscountAmount   += $sale->discount_amount;
        } 
        $data =[];
        $data['total_sales_price']      = $totalSalesPrice;
        $data['total_tax_amount']       = $totalTaxAmount;
        $data['total_shipping_charge']  = $totalShippingCharge;
        $data['total_discount_amount']  = $totalDiscountAmount; 
        return $data;
    }
 

    public function getPosData($request){
        $date = explode(' - ', $request->date);
        if(is_array($date)) {
            $from   = Carbon::parse(trim($date[0]))->startOfDay()->toDateTimeString();
            $to     = Carbon::parse(trim($date[1]))->endOfDay()->toDateTimeString(); 
        }  
        $poses      = Pos::with(['posItems'])->where(function($query)use($request){
                                $query->where('business_id',business_id());
                                if(isUser()):
                                    $query->where('branch_id',Auth::user()->branch_id);
                                elseif(business() && !blank($request->branch_id)):
                                    $query->where('branch_id',$request->branch_id);
                                endif;
                        })->whereBetween('updated_at',[$from,$to])->get();
        $totalposPrice         = 0; 
        $totalTaxAmount        = 0; 
        $totalShippingCharge   = 0; 
        $totalDiscountAmount   = 0; 
        foreach ($poses as  $pos) {  
            $totalposPrice         += $pos->posItems->sum('total_unit_price');
            $totalTaxAmount        += $pos->total_tax_amount;
            $totalShippingCharge   += $pos->shipping_charge;
            $totalDiscountAmount   += $pos->discount_amount;
        } 
        $data =[];
        $data['total_pos_price']        = $totalposPrice;
        $data['total_tax_amount']       = $totalTaxAmount;
        $data['total_shipping_charge']  = $totalShippingCharge;
        $data['total_discount_amount']  = $totalDiscountAmount; 
        return $data;
    }


    public function getPurchaseData($request){
        $date = explode(' - ', $request->date);
        if(is_array($date)) {
            $from   = Carbon::parse(trim($date[0]))->startOfDay()->toDateTimeString();
            $to     = Carbon::parse(trim($date[1]))->endOfDay()->toDateTimeString(); 
        }  
        $purchases    = Purchase::with(['purchaseItems'])->where(function($query)use($request){
                            $query->where('business_id',business_id());
                            $query->whereHas('purchaseItems',function($query)use($request){
                                if(isUser()):
                                    $query->where('branch_id',Auth::user()->branch_id);
                                elseif(business() && !blank($request->branch_id)):
                                    $query->where('branch_id',$request->branch_id);
                                endif;
                            });
                        })->whereBetween('updated_at',[$from,$to])->get();
        $totalpurchasecost        = 0; 
        $totalpurchasetax         = 0;  
        foreach ($purchases as  $purchase) {  
            if(isUser()):
                $totalUnitCost     = $purchase->purchaseItems->where('branch_id',Auth::user()->branch_id)->sum('total_unit_cost'); 
            elseif(business() && !blank($request->branch_id)):
                $totalUnitCost     = $purchase->purchaseItems->where('branch_id',$request->branch_id)->sum('total_unit_cost'); 
            else:
                $totalUnitCost     = $purchase->purchaseItems->sum('total_unit_cost'); 
            endif; 
            $totalpurchasecost         += $totalUnitCost;
            $totalpurchasetax          += (($totalUnitCost/100)*$purchase->TaxRate->tax_rate); 
        } 
        $data = [];
        $data['total_purchase_cost']        = $totalpurchasecost;
        $data['total_tax_amount']           = $totalpurchasetax;  
        return $data;
    }
    public function getPurchaseReturnData($request){
        $date = explode(' - ', $request->date);
        if(is_array($date)) {
            $from   = Carbon::parse(trim($date[0]))->startOfDay()->toDateTimeString();
            $to     = Carbon::parse(trim($date[1]))->endOfDay()->toDateTimeString(); 
        }  
        $purchasesReturn   = PurchaseReturn::with(['purchaseReturnItems'])->where(function($query)use($request){
                            $query->where('business_id',business_id());
                            $query->whereHas('purchaseReturnItems',function($query)use($request){
                                if(isUser()):
                                    $query->where('branch_id',Auth::user()->branch_id);
                                elseif(business() && !blank($request->branch_id)):
                                    $query->where('branch_id',$request->branch_id);
                                endif;
                            });
                        })->whereBetween('updated_at',[$from,$to])->get();
        $totalpurchasereturnprice        = 0; 
        $totalpurchasereturntax          = 0;  
        foreach ($purchasesReturn as  $purchase) {  
            if(isUser()):
                $totalUnitPrice     = $purchase->purchaseReturnItems->where('branch_id',Auth::user()->branch_id)->sum('total_unit_cost'); 
            elseif(business() && !blank($request->branch_id)):
                $totalUnitPrice     = $purchase->purchaseReturnItems->where('branch_id',$request->branch_id)->sum('total_unit_price'); 
            else:
                $totalUnitPrice     = $purchase->purchaseReturnItems->sum('total_unit_price'); 
            endif; 
            $totalpurchasereturnprice         += $totalUnitPrice;
            $totalpurchasereturntax           += (($totalUnitPrice/100)*$purchase->TaxRate->tax_rate); 
        } 
        $data = [];
        $data['total_purchase_return_price'] = $totalpurchasereturnprice;
        $data['total_tax_amount']            = $totalpurchasereturntax;  
        return $data;
    } 

    public function getStocktransferData($request){
        $date = explode(' - ', $request->date);
        if(is_array($date)) {
            $from   = Carbon::parse(trim($date[0]))->startOfDay()->toDateTimeString();
            $to     = Carbon::parse(trim($date[1]))->endOfDay()->toDateTimeString(); 
        }  
        $stocktransfer   = StockTransfer::with(['TransferItems'])->where(function($query)use($request){
                            $query->where('business_id',business_id()); 
                            if(isUser()):
                                $query->where('from_branch',Auth::user()->branch_id);
                            elseif(business() && !blank($request->branch_id)):
                                $query->where('from_branch',$request->branch_id);
                            endif; 
                        })->whereBetween('updated_at',[$from,$to])->get();

        $totaltransferprice           = 0; 
        $totalshippingcharge          = 0;  
        foreach ($stocktransfer as  $transfer) {   
            $totalUnitPrice              = $transfer->TransferItems->sum('total_unit_price');  
            $totaltransferprice         += $totalUnitPrice;
            $totalshippingcharge        += $transfer->shipping_charge; 
        } 
        $data = [];
        $data['total_transfer_price']   = $totaltransferprice;
        $data['total_shipping_charge']  = $totalshippingcharge;  
        return $data;
    }


    public function getIncomeData($request){
        $date = explode(' - ', $request->date);
        if(is_array($date)) {
            $from   = Carbon::parse(trim($date[0]))->startOfDay()->toDateTimeString();
            $to     = Carbon::parse(trim($date[1]))->endOfDay()->toDateTimeString(); 
        } 

        $income = Income::where(function($query)use($request){
                        $query->where('business_id',business_id());
                        if(isUser()):
                            $query->where('branch_id',Auth::user()->branch_id);
                        elseif(business() && !blank($request->branch_id)):
                            $query->where('branch_id',$request->branch_id);
                        endif;
                    })->whereBetween('updated_at',[$from,$to])->sum('amount');
        $data = [];
        $data['total_income']  = $income;
        return $data;
    }
    public function getExpenseData($request){
        $date = explode(' - ', $request->date);
        if(is_array($date)) {
            $from   = Carbon::parse(trim($date[0]))->startOfDay()->toDateTimeString();
            $to     = Carbon::parse(trim($date[1]))->endOfDay()->toDateTimeString(); 
        }  
        $expense = Expense::where(function($query)use($request){
                        $query->where('business_id',business_id());
                        if(isUser()):
                            $query->where('branch_id',Auth::user()->branch_id);
                        elseif(business() && !blank($request->branch_id)):
                            $query->where('branch_id',$request->branch_id);
                        endif;
                    })->whereBetween('updated_at',[$from,$to])->sum('amount');
        $data = [];
        $data['total_expense']  = $expense;
        return $data;
    }
}