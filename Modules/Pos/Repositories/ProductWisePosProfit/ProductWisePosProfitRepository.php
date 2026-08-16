<?php
namespace Modules\Pos\Repositories\ProductWisePosProfit;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth; 
use Modules\Product\Entities\VariationLocationDetails;
use Modules\Pos\Repositories\ProductWisePosProfit\ProductWisePosProfitInterface;
 

class ProductWisePosProfitRepository implements ProductWisePosProfitInterface{
    public function getProfit($request){
      
        $date = explode(' - ', $request->date);
        if(is_array($date)) {
            $from   = Carbon::parse(trim($date[0]))->startOfDay()->toDateTimeString();
            $to     = Carbon::parse(trim($date[1]))->endOfDay()->toDateTimeString(); 
        }   
        $pos_variations_locations   =   VariationLocationDetails::with('posItems')->where(function($query)use($request,$from,$to){
                                            $query->where('business_id',business_id());
                                            if(isUser()):
                                                $query->where('branch_id',Auth::user()->branch_id);
                                            else:
                                                if($request->branch_id):
                                                    $query->where('branch_id',$request->branch_id);
                                                endif;
                                            endif;
                                            $query->whereHas('posItems',function($query)use($request,$from,$to){
                                                $query->whereBetween('updated_at',[$from,$to]);
                                            }); 
                                        })->get(); 
        $products = collect($pos_variations_locations)->groupBy('product_variation_id')->paginate(10);
        return $products;
    }
}