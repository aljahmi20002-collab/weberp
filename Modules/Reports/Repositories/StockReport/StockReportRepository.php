<?php
namespace Modules\Reports\Repositories\StockReport;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth; 
use Modules\Product\Entities\VariationLocationDetails;

class StockReportRepository implements StockReportInterface{
    public function getReport($request){ 
        return VariationLocationDetails::with(['branch','product','ProductVariation' ])->where(function($query)use($request){ 
            $query->where('business_id',business_id()); 
            if(isUser()):
                $query->where('branch_id',Auth::user()->branch_id);
            elseif($request->branch_id && !blank($request->branch_id)):
                $query->where('branch_id',$request->branch_id);
            endif; 
            $query->whereHas('product',function($query)use($request){
                if($request->category_id && !blank($request->category_id)): 
                    $query->where('category_id',$request->category_id);
                endif;
                if($request->subcategory_id && !blank($request->subcategory_id)): 
                    $query->where('subcategory_id',$request->subcategory_id);
                endif;
                if($request->brand_id && !blank($request->brand_id)): 
                    $query->where('brand_id',$request->brand_id);
                endif;
                if($request->unit_id && !blank($request->unit_id)): 
                    $query->where('unit_id',$request->unit_id);
                endif;
            }); 
        })->orderByDesc('id')->paginate(10); 
    }

    public function getTotalCalculation($ProductVariations){
        $data = []; 
        $data['total_current_stock_purchase_price']    = 0;
        $data['total_current_stock_selling_price']     = 0;
        $data['total_current_stock_tax']               = 0;
        $data['total_current_stock_gross_profit']      = 0;
        foreach ($ProductVariations as $variationDetails) { 
            $data['total_current_stock_purchase_price']    +=  $variationDetails->ProductVariation->default_purchase_price * $variationDetails->qty_available;
            $data['total_current_stock_selling_price']     +=  $variationDetails->ProductVariation->default_sell_price * $variationDetails->qty_available; 
            $data['total_current_stock_tax']               +=  (($variationDetails->ProductVariation->default_sell_price/100) * $variationDetails->product->TaxRate->tax_rate) * $variationDetails->qty_available;
        } 
        $data['total_current_stock_gross_profit']      =  ($data['total_current_stock_selling_price'] -  $data['total_current_stock_purchase_price']); 

        $data['total_current_stock_purchase_price']    = number_format($data['total_current_stock_purchase_price'],2);
        $data['total_current_stock_selling_price']     = number_format($data['total_current_stock_selling_price'],2);
        $data['total_current_stock_tax']               = number_format($data['total_current_stock_tax'],2);
        $data['total_current_stock_gross_profit']      = number_format($data['total_current_stock_gross_profit'],2);

        return response()->json($data);
    }
}