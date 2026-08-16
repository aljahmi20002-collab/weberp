<?php
namespace Modules\Reports\Repositories\ProductWiseProfit;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Modules\Pos\Entities\Pos;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\VariationLocationDetails;
use Modules\Reports\Repositories\ProductWiseProfit\ProductWiseProfitInterface;
use Modules\Sell\Entities\Sale;

class ProductWiseProfitRepository implements ProductWiseProfitInterface{
    public function getProfit($request){
        $date = explode(' - ', $request->date);
        if(is_array($date)) {
            $from   = Carbon::parse(trim($date[0]))->startOfDay()->toDateTimeString();
            $to     = Carbon::parse(trim($date[1]))->endOfDay()->toDateTimeString(); 
        }   
        $sale_variations_locations   =   VariationLocationDetails::with('saleItems')->where(function($query)use($request,$from,$to){
                                            $query->where('business_id',business_id());
                                            if(isUser()):
                                                $query->where('branch_id',Auth::user()->branch_id);
                                            else:
                                                if($request->branch_id):
                                                    $query->where('branch_id',$request->branch_id);
                                                endif;
                                            endif;
                                            $query->whereHas('saleItems',function($query)use($request,$from,$to){
                                                $query->whereBetween('updated_at',[$from,$to]);
                                            }); 
                                        })->get(); 
        $products = collect($sale_variations_locations)->groupBy('product_variation_id')->paginate(10);
        return $products;
    }
}