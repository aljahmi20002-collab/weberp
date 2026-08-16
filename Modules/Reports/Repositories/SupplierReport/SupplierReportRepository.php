<?php
namespace Modules\Reports\Repositories\SupplierReport;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Modules\Purchase\Entities\Purchase;
use Modules\Purchase\Entities\PurchaseReturn;

class SupplierReportRepository implements SupplierReportInterface{
    public function getPurchaseReport($request){

        $date = explode(' - ', $request->date);
        if(is_array($date)) {
            $from   = Carbon::parse(trim($date[0]))->startOfDay()->toDateTimeString();
            $to     = Carbon::parse(trim($date[1]))->endOfDay()->toDateTimeString(); 
        }  

        $purchase   =   Purchase::with('purchaseItems')->where(function($query)use($request){
                            $query->where('business_id',business_id());
                            if($request->supplier_id):
                                $query->where('supplier_id',$request->supplier_id);
                            endif;
                            if(isUser()):
                                $query->whereHas('purchaseItems',function($query)use($request){
                                        $query->where('branch_id',Auth::user()->branch_id);
                                });
                            else:
                                if($request->branch_id):
                                    $query->whereHas('purchaseItems',function($query)use($request){
                                        $query->where('branch_id',$request->branch_id);
                                    });
                                endif;
                            endif; 
                        })->orderByDesc('id')->whereBetween('updated_at',[$from,$to])->paginate(10); 
        return $purchase;
    }
    //purchase return 
    public function getPurchaseReturnReport($request){
        $date = explode(' - ', $request->date);
        if(is_array($date)) {
            $from   = Carbon::parse(trim($date[0]))->startOfDay()->toDateTimeString();
            $to     = Carbon::parse(trim($date[1]))->endOfDay()->toDateTimeString(); 
        }  

        $purchasereturn   =   PurchaseReturn::with('purchaseReturnItems')->where(function($query)use($request){
                                $query->where('business_id',business_id());
                                if($request->supplier_id):
                                    $query->where('supplier_id',$request->supplier_id);
                                endif;
                                if(isUser()):
                                    $query->whereHas('purchaseReturnItems',function($query)use($request){
                                            $query->where('branch_id',Auth::user()->branch_id);
                                    });
                                else:
                                    if($request->branch_id):
                                        $query->whereHas('purchaseReturnItems',function($query)use($request){
                                            $query->where('branch_id',$request->branch_id);
                                        });
                                    endif;
                                endif; 
                            })->orderByDesc('id')->whereBetween('updated_at',[$from,$to])->paginate(10);
        
        return $purchasereturn;
    }
}