<?php
namespace App\Repositories\BranchDashboard;
use App\Repositories\BranchDashboard\BranchDashboardInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Modules\Expense\Entities\Expense;
use Modules\Pos\Entities\Pos;
use Modules\Purchase\Entities\Purchase;
use Modules\Purchase\Entities\PurchaseReturn;
use Modules\Sell\Entities\Sale;

class BranchDashboardRepository implements BranchDashboardInterface {
    public function totalSales($request){
        $data = [];
        //sales
        $data['total_sales_amount']  = 0;
        $data['total_payments']      = 0;
        $data['total_due']           = 0;
        $data['total_expense']       = 0;
        $data['total_net']           = 0;
     
         
        $date = explode(' - ', $request->date);
        if(is_array($date)) {
            $from   = Carbon::parse(trim($date[0]))->startOfDay()->toDateTimeString();
            $to     = Carbon::parse(trim($date[1]))->endOfDay()->toDateTimeString(); 
        }    
        $sales  =   Sale::with('payments','TaxRate','saleItems')->where(function($query)use($request,$from,$to){
                        $query->where('business_id',business_id()); 
                        $query->where('branch_id',Auth::user()->branch_id); 
                        if($request->lifetime != 'lifetime'):
                            $query->whereBetween('updated_at',[$from,$to]);
                        endif;
                    })->get();

        $data['total_expense'] = Expense::where(function($query)use($request,$from,$to){
                                    $query->where('business_id',business_id()); 
                                    $query->where('branch_id',Auth::user()->branch_id); 
                                    if($request->lifetime != 'lifetime'):
                                        $query->whereBetween('updated_at',[$from,$to]);
                                    endif;
                                 })->sum('amount');

        foreach ($sales as   $sale) {
            $data['total_sales_amount']  += $sale->TotalSalePrice;
            $data['total_payments']      += $sale->payments->sum('amount');
            $data['total_due']           += $sale->DueAmount;
        }
        $data['total_net']           = ($data['total_sales_amount'] - $data['total_due'] ) - $data['total_expense'];
   
        return $data;
    }

    public function totalPos($request){
        $data = []; 
         //pos
        $data['total_pos_amount']    = 0;
        $data['total_pos_payments']  = 0;
        $data['total_pos_due']       = 0;

        $date = explode(' - ', $request->date);
        if(is_array($date)) {
            $from   = Carbon::parse(trim($date[0]))->startOfDay()->toDateTimeString();
            $to     = Carbon::parse(trim($date[1]))->endOfDay()->toDateTimeString(); 
        }   
        //pos
        $poses  =   Pos::with('payments','TaxRate','posItems')->where(function($query)use($request,$from,$to){
                        $query->where('business_id',business_id()); 
                        $query->where('branch_id',Auth::user()->branch_id); 
                        if($request->lifetime != 'lifetime'):
                            $query->whereBetween('updated_at',[$from,$to]);
                        endif;
                    })->get();

        foreach ($poses as  $pos) {    
            $data['total_pos_amount']    += $pos->TotalSalePrice;
            $data['total_pos_payments']  += $pos->payments->sum('amount');
            $data['total_pos_due']       += $pos->DueAmount;
        }
        return $data;
    }

    public function totalPurchase($request){
        $data = []; 
        //pos
       $data['total_purchase_items']     = 0;
       $data['total_purchase_amount']    = 0;
       $data['total_purchase_payments']  = 0;
       $data['total_purchase_due']       = 0;

       $date = explode(' - ', $request->date);
       if(is_array($date)) {
           $from   = Carbon::parse(trim($date[0]))->startOfDay()->toDateTimeString();
           $to     = Carbon::parse(trim($date[1]))->endOfDay()->toDateTimeString(); 
       }   
       //pos
       $purchases  =   Purchase::with('payments','TaxRate','purchaseItems')->where(function($query)use($request,$from,$to){
                       $query->where('business_id',business_id()); 
                          $query->whereHas('purchaseItems',function($query)use($request){
                              $query->where('branch_id',Auth::user()->branch_id);
                          }); 
                        if($request->lifetime != 'lifetime'):
                            $query->whereBetween('updated_at',[$from,$to]);
                        endif;
                   })->get();

       foreach ($purchases as  $purchase) {    
           $data['total_purchase_items']     += $purchase->purchaseItems->sum('purchase_quantity');
           $data['total_purchase_amount']    += $purchase->TotalPurchaseCost;
           $data['total_purchase_payments']  += $purchase->payments->sum('amount');
           $data['total_purchase_due']       += $purchase->DueAmount;
       }
       return $data;
    }


    public function TotalPurchaseReturn($request){
        $data = []; 
        //pos
       $data['total_purchase_return_items']     = 0;
       $data['total_purchase_return_amount']    = 0;
       $data['total_purchase_return_payments']  = 0;
       $data['total_purchase_return_due']       = 0;

       $date = explode(' - ', $request->date);
       if(is_array($date)) {
           $from   = Carbon::parse(trim($date[0]))->startOfDay()->toDateTimeString();
           $to     = Carbon::parse(trim($date[1]))->endOfDay()->toDateTimeString(); 
       }   
       //pos
       $purchases  =   PurchaseReturn::with('payments','TaxRate','purchaseReturnItems')->where(function($query)use($request,$from,$to){
                       $query->where('business_id',business_id()); 
                        $query->whereHas('purchaseReturnItems',function($query)use($request){
                            $query->where('branch_id',Auth::user()->branch_id);
                        }); 
                        if($request->lifetime != 'lifetime'):
                            $query->whereBetween('updated_at',[$from,$to]);
                        endif;
                    })->get();

       foreach ($purchases as  $purchase) {    
           $data['total_purchase_return_items']     += $purchase->purchaseReturnItems->sum('return_quantity');
           $data['total_purchase_return_amount']    += $purchase->TotalPurchaseReturnPrice;
           $data['total_purchase_return_payments']  += $purchase->payments->sum('amount');
           $data['total_purchase_return_due']       += $purchase->DueAmount;
       }
       return $data;
    }


    
    public function ThirtyDaysSalesChart(){
        $data =[];
        $start_date = Carbon::today()->subDays(30)->startOfDay()->toDateTimeString();
        $end_date   = Carbon::today()->endOfDay()->toDateTimeString();
        $data['dates']                 = []; 
        $data['sales_amount']          = []; 
        $data['sales_payment']         = [];  
        for ($i=0; $i <30 ; $i++) { 
            $d                  = Carbon::parse($start_date)->addDay($i)->format('d-m-Y');  
            $data['dates'][]    = $d;
            $start_d            = Carbon::parse($start_date)->addDay($i)->startOfDay()->toDateTimeString();
            $end_d              = Carbon::parse($start_date)->addDay($i)->endOfDay()->toDateTimeString();
            $sales              = Sale::with('saleItems','payments','business','TaxRate')
                                        ->where('business_id',business_id())
                                        ->where('branch_id',Auth::user()->branch_id)
                                        ->whereBetween('updated_at',[$start_d,$end_d])->get(); 
                                        
            $totalAmount        = 0;
            $totalPayment       = 0;
            foreach ($sales as $sale) {
                $totalAmount  += $sale->TotalSalePrice;
                $totalPayment += $sale->payments->sum('amount');
            }
            $data['sales_amount'][$d]         = $totalAmount; 
            $data['sales_payment'][$d]        = $totalPayment; 
        } 
        return $data;
    }

    public function recentSales($request){
        return Sale::with('saleItems','payments','TaxRate','business')->where(function($query)use($request){
                            $query->where('business_id',business_id()); 
                            $query->where('branch_id',Auth::user()->branch_id); 
                        })->orderByDesc('id')->limit(5)->get();
    }

    public function recentPos($request){
        return Pos::with('posItems','payments','TaxRate','business')->where(function($query)use($request){
                            $query->where('business_id',business_id()); 
                            $query->where('branch_id',Auth::user()->branch_id); 
                        })->orderByDesc('id')->limit(5)->get();
    }
}