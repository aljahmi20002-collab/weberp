<?php

namespace App\Repositories\BusinessDashboard;

use App\Repositories\BusinessDashboard\BusinessDashboardInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Modules\Expense\Entities\Expense;
use Modules\Pos\Entities\Pos;
use Modules\Product\Entities\Product;
use Modules\Purchase\Entities\Purchase;
use Modules\Purchase\Entities\PurchaseReturn;
use Modules\Sell\Entities\Sale;
use Modules\ServiceSale\Entities\ServiceSale;

class BusinessDashboardRepository implements BusinessDashboardInterface
{
    public function totalSales($request)
    {

        $data = [];
        //sales
        $data['total_sales_amount']  = 0;
        $data['total_payments']      = 0;
        $data['total_due']           = 0;
        $data['total_expense']       = 0;
        $data['total_net']           = 0;


        $date = explode(' - ', $request->date);
        if (is_array($date)) {
            $from   = Carbon::parse(trim($date[0]))->startOfDay()->toDateTimeString();
            $to     = Carbon::parse(trim($date[1]))->endOfDay()->toDateTimeString();
        }
        $sales  =   Sale::with('payments', 'TaxRate', 'saleItems')->where(function ($query) use ($request, $from, $to) {
            $query->where('business_id', business_id());
            if ($request->branch_id) :
                $query->where('branch_id', $request->branch_id);
            endif;
            if ($request->lifetime != 'lifetime') :
                $query->whereBetween('updated_at', [$from, $to]);
            endif;
        })->get();

        $data['total_expense'] = Expense::where(function ($query) use ($request, $from, $to) {
            $query->where('business_id', business_id());
            if ($request->branch_id) :
                $query->where('branch_id', $request->branch_id);
            endif;
            if ($request->lifetime != 'lifetime') :
                $query->whereBetween('updated_at', [$from, $to]);
            endif;
        })->sum('amount');

        foreach ($sales as   $sale) {
            $data['total_sales_amount']  += $sale->TotalSalePrice;
            $data['total_payments']      += $sale->payments->sum('amount');
            $data['total_due']           += $sale->DueAmount;
        }
        $data['total_net']           = ($data['total_sales_amount'] - $data['total_due']) - $data['total_expense'];

        return $data;
    }

    public function totalPos($request)
    {
        $data = [];
        //pos
        $data['total_pos_amount']    = 0;
        $data['total_pos_payments']  = 0;
        $data['total_pos_due']       = 0;

        $date = explode(' - ', $request->date);
        if (is_array($date)) {
            $from   = Carbon::parse(trim($date[0]))->startOfDay()->toDateTimeString();
            $to     = Carbon::parse(trim($date[1]))->endOfDay()->toDateTimeString();
        }
        //pos
        $poses  =   Pos::with('payments', 'TaxRate', 'posItems')->where(function ($query) use ($request, $from, $to) {
            $query->where('business_id', business_id());
            if ($request->branch_id) :
                $query->where('branch_id', $request->branch_id);
            endif;
            if ($request->lifetime != 'lifetime') :
                $query->whereBetween('updated_at', [$from, $to]);
            endif;
        })->get();

        foreach ($poses as  $pos) {
            $data['total_pos_amount']    += $pos->TotalSalePrice;
            $data['total_pos_payments']  += $pos->payments->sum('amount');
            $data['total_pos_due']       += $pos->DueAmount;
        }
        return $data;
    }

    public function totalPurchase($request)
    {
        $data = [];
        //pos
        $data['total_purchase_items']     = 0;
        $data['total_purchase_amount']    = 0;
        $data['total_purchase_payments']  = 0;
        $data['total_purchase_due']       = 0;

        $date = explode(' - ', $request->date);
        if (is_array($date)) {
            $from   = Carbon::parse(trim($date[0]))->startOfDay()->toDateTimeString();
            $to     = Carbon::parse(trim($date[1]))->endOfDay()->toDateTimeString();
        }
        //purchase
        $purchases  =   Purchase::with('payments', 'TaxRate', 'purchaseItems')->where(function ($query) use ($request, $from, $to) {
            $query->where('business_id', business_id());
            if ($request->branch_id) :
                $query->whereHas('purchaseItems', function ($query) use ($request) {
                    $query->where('branch_id', $request->branch_id);
                });
            endif;
            if ($request->lifetime != 'lifetime') :
                $query->whereBetween('updated_at', [$from, $to]);
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


    public function TotalPurchaseReturn($request)
    {
        $data = [];
        //purchase return
        $data['total_purchase_return_items']     = 0;
        $data['total_purchase_return_amount']    = 0;
        $data['total_purchase_return_payments']  = 0;
        $data['total_purchase_return_due']       = 0;

        $date = explode(' - ', $request->date);
        if (is_array($date)) {
            $from   = Carbon::parse(trim($date[0]))->startOfDay()->toDateTimeString();
            $to     = Carbon::parse(trim($date[1]))->endOfDay()->toDateTimeString();
        }
        //purchase return
        $purchases  =   PurchaseReturn::with('payments', 'TaxRate', 'purchaseReturnItems')->where(function ($query) use ($request, $from, $to) {
            $query->where('business_id', business_id());
            if ($request->branch_id) :
                $query->whereHas('purchaseReturnItems', function ($query) use ($request) {
                    $query->where('branch_id', $request->branch_id);
                });
            endif;

            if ($request->lifetime !=  'lifetime') :
                $query->whereBetween('updated_at', [$from, $to]);
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



    public function ThirtyDaysSalesChart()
    {
        $data = [];
        $start_date = Carbon::today()->subDays(30)->startOfDay()->toDateTimeString();
        $end_date   = Carbon::today()->endOfDay()->toDateTimeString();
        $data['dates']                 = [];
        $data['sales_amount']          = [];
        $data['sales_payment']         = [];
        for ($i = 0; $i < 30; $i++) {
            $d                  = Carbon::parse($start_date)->addDay($i)->format('d-m-Y');
            $data['dates'][]    = $d;
            $start_d            = Carbon::parse($start_date)->addDay($i)->startOfDay()->toDateTimeString();
            $end_d              = Carbon::parse($start_date)->addDay($i)->endOfDay()->toDateTimeString();
            $sales              = Sale::with('saleItems', 'payments', 'business', 'TaxRate')->where('business_id', business_id())->whereBetween('updated_at', [$start_d, $end_d])->get();
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

    public function recentSales($request)
    {
        return Sale::with('saleItems', 'payments', 'TaxRate', 'business')->where(function ($query) use ($request) {
            $query->where('business_id', business_id());
            if ($request->branch_id) :
                $query->where('branch_id', $request->branch_id);
            endif;
        })->orderByDesc('id')->limit(5)->get();
    }

    public function recentPos($request)
    {
        return Pos::with('posItems', 'payments', 'TaxRate', 'business')->where(function ($query) use ($request) {
            $query->where('business_id', business_id());
            if ($request->branch_id) :
                $query->where('branch_id', $request->branch_id);
            endif;
        })->orderByDesc('id')->limit(5)->get();
    }



    public function summeryCount($request)
    {
     
        $data=[];
        $data['total_product_count']         = 0;
        $data['total_sales_count']           = 0;
        $data['total_service_sale_count']    = 0;
        $data['total_pos_count']             = 0;
        $data['total_purchase_count']        = 0;
        $data['total_purchase_return_count'] = 0;

        $data=[];
        $date = explode('To', $request->date);
        
        $from =null;
        $to   =null;
        if ($request->date && is_array($date)) {
            $from   = Carbon::parse(trim($date[0]))->startOfDay()->toDateTimeString();
            $to     = Carbon::parse(trim($date[1]))->endOfDay()->toDateTimeString();
        }
  
        // //products 
        $data['total_product_count'] = Product::where(function ($query) use ($request, $from, $to) { 
                $query->where('business_id', business_id()); 
                if ($request->date && !empty($request->date)):
                    $query->whereBetween('updated_at', [$from, $to]);
                endif;
        })->count();

        //sales
        $data['total_sales_count'] =   Sale::with('payments', 'TaxRate', 'saleItems')->where(function ($query) use ($request, $from, $to) {
            $query->where('business_id', business_id());
            if ($request->branch_id) :
                $query->where('branch_id', $request->branch_id);
            endif;
            if ($request->date && !empty($request->date)) :
                $query->whereBetween('updated_at', [$from, $to]);
            endif;
        })->count();

        //pos
        $data['total_pos_count']=   Pos::with('payments', 'TaxRate', 'posItems')->where(function ($query) use ($request, $from, $to) {
            $query->where('business_id', business_id());
            if ($request->branch_id) :
                $query->where('branch_id', $request->branch_id);
            endif;
            if ($request->date && !empty($request->date)) :
                $query->whereBetween('updated_at', [$from, $to]);
            endif;
        })->count();

        //purchase 
        $data['total_purchase_count'] =   Purchase::with('payments', 'TaxRate', 'purchaseItems')->where(function ($query) use ($request, $from, $to) {
            $query->where('business_id', business_id());
            if ($request->branch_id) :
                $query->whereHas('purchaseItems', function ($query) use ($request) {
                    $query->where('branch_id', $request->branch_id);
                });
            endif;
            if ($request->date && !empty($request->date)) :
                $query->whereBetween('updated_at', [$from, $to]);
            endif;
        })->count();


        //purchase return 
        $data['total_purchase_return_count'] =   PurchaseReturn::with('payments', 'TaxRate', 'purchaseReturnItems')->where(function ($query) use ($request, $from, $to) {
            $query->where('business_id', business_id());
            if ($request->branch_id) :
                $query->whereHas('purchaseReturnItems', function ($query) use ($request) {
                    $query->where('branch_id', $request->branch_id);
                });
            endif;

            if ($request->date && !empty($request->date)) :
                $query->whereBetween('updated_at', [$from, $to]);
            endif;
        })->count();
 
        $data['total_service_sale_count'] = ServiceSale::where(function($query)use ($request, $from, $to){
            $query->where('business_id',business_id());
            if ($request->date && !empty($request->date)) :
                $query->whereBetween('updated_at', [$from, $to]);
            endif;
        })->count();  
        return $data;
    }



    public function salesChartsData(){ 
        $salesData = [];
        $start_year = Carbon::now()->startOfYear()->format('m');
        $end_year = Carbon::now()->endOfYear()->format('m');
        for ($start_year; $start_year <= $end_year; $start_year++) {  
            $date = '1-'.$start_year.'-'.Carbon::now()->format('Y'); 
            $month_name=Carbon::parse($date)->format('M'); 
            $from  = Carbon::parse($date)->startOfMonth()->toDateTimeString();
            $to    = Carbon::parse($date)->endOfMonth()->addSecond(1)->toDateTimeString(); 
            $salesData[$month_name] = Sale::where(function ($query) use ($from, $to) {
                    $query->where('business_id', business_id());  
                    $query->whereBetween('updated_at', [$from, $to]); 
                })->count();
        }  
        return $salesData;
    }


    public function purchaseChartsData(){ 
        $salesData = [];
        $start_year = Carbon::now()->startOfYear()->format('m');
        $end_year = Carbon::now()->endOfYear()->format('m');
        for ($start_year; $start_year <= $end_year; $start_year++) {  
            $date = '1-'.$start_year.'-'.Carbon::now()->format('Y'); 
            $month_name=Carbon::parse($date)->format('M'); 
            $from  = Carbon::parse($date)->startOfMonth()->toDateTimeString();
            $to    = Carbon::parse($date)->endOfMonth()->addSecond(1)->toDateTimeString(); 
            $salesData[$month_name] = Purchase::where(function ($query) use ($from, $to) {
                    $query->where('business_id', business_id());  
                    $query->whereBetween('updated_at', [$from, $to]); 
                })->count();
        }  
        return $salesData;
    }
  
}
