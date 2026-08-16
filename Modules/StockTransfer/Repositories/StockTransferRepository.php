<?php
namespace Modules\StockTransfer\Repositories;

use Illuminate\Support\Facades\Auth;
use Modules\Product\Entities\VariationLocationDetails;
use Modules\StockTransfer\Entities\StockTransfer;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Modules\StockTransfer\Entities\StockTransferItem;
use Modules\StockTransfer\Enums\StockTransferStatus;

class StockTransferRepository implements StockTransferInterface {
    protected $model;
    public function __construct(StockTransfer $model)
    {
        $this->model  = $model; 
    }
    public function get(){
        return $this->model::where(function($query){
            if(business()):
                $query->where('business_id',business_id());
            elseif(isUser()):
                $query->where('business_id',business_id());
                $query->where('to_branch',Auth::user()->branch_id);
            endif;
        })->orderByDesc('id')->paginate(10);
    }
    
    public function getAllTransfer(){
        return $this->model::where(function($query){
            if(business()):
                $query->where('business_id',business_id());
            elseif(isUser()):
                $query->where('business_id',business_id());
                $query->where('to_branch',Auth::user()->branch_id);
            endif;
        })->orderByDesc('id')->get();
    }
    
    public function getFind($id){
        return $this->model::find($id);
    }

    public function store($request){
      
        try {
            $stockTransfer                  = new $this->model();
            $stockTransfer->business_id     = business_id(); 
            $stockTransfer->transfer_no     = $this->TransferNo();
            $stockTransfer->from_branch     = $request->branch_id;
            $stockTransfer->to_branch       = $request->to_branch; 
            $stockTransfer->shipping_charge = $request->shipping_charge;   
            $stockTransfer->total_transfer_amount    = $request->total_amount;   
            $stockTransfer->created_by      = Auth::user()->id;
            $stockTransfer->save();
            $request['stock_transfer_id'] = $stockTransfer->id?? null;
            if($stockTransfer && $this->stockTransferItemStore($request)): 
                return true;
            else:
                DB::rollBack();
                return false;
            endif;
        } catch (\Throwable $th) { 
            DB::rollBack();
            return false;
        }
    }

    public function stockTransferItemStore($request){ 
        try { 
            foreach ($request->variation_locations as $variationLocation) {
                $variationLocationDetails              = VariationLocationDetails::find($variationLocation['id']);

                $receivedVariation = VariationLocationDetails::where('branch_id',$request->to_branch)->where('product_variation_id',$variationLocationDetails->product_variation_id)->first(); 
                if(!$receivedVariation):  
                    $receivedVariationId = $this->storeVariationLocationDetails($variationLocationDetails,$request,0);
                endif; 
  
                $stockTransferItem                     = new StockTransferItem();
                $stockTransferItem->stock_transfer_id  = $request->stock_transfer_id;
                $stockTransferItem->business_id        = business_id(); 
                $stockTransferItem->vari_loc_det_id    = $variationLocationDetails->id;
                $stockTransferItem->to_vari_loc_det_id = $receivedVariationId->id?? $receivedVariation->id;
                $stockTransferItem->quantity           = $variationLocation['quantity'];
                $stockTransferItem->unit_price         = $variationLocation['unit_price'];
                $stockTransferItem->total_unit_price   = ($variationLocation['unit_price'] * $variationLocation['quantity']);  
                $stockTransferItem->save();  
            } 
            return true;
        }catch (\Throwable $th) {  
            DB::rollBack();
            return false;
        }
    }

    public function storeVariationLocationDetails($variationLocation,$request,$quantity){
        $variationLocationDet               = new VariationLocationDetails();
        $variationLocationDet->business_id  = business_id();
        $variationLocationDet->branch_id    = $request->to_branch;
        $variationLocationDet->product_id   = $variationLocation->product_id;
        $variationLocationDet->variation_id = $variationLocation->variation_id; 
        $variationLocationDet->product_variation_id = $variationLocation->product_variation_id;
        $variationLocationDet->qty_available = $quantity; 
        $variationLocationDet->save();
        return $variationLocationDet;
    }
 
    public function TransferNo(){
        return date('dmyhis').Str::random(3);
    }
    public function update($id,$request){
        try {
            $stockTransfer   = $this->getFind($id); 
            foreach ($stockTransfer->TransferItems as $item) { 
                StockTransferItem::destroy($item->id); 
            }  
            $stockTransfer->from_branch     = $request->branch_id;
            $stockTransfer->to_branch       = $request->to_branch; 
            $stockTransfer->shipping_charge = $request->shipping_charge; 
            $stockTransfer->total_transfer_amount    = $request->total_amount;   
            $stockTransfer->created_by      = Auth::user()->id;  
            $stockTransfer->save();
            $request['stock_transfer_id'] = $stockTransfer->id?? null;
            if($stockTransfer && $this->stockTransferItemStore($request)): 
                return true;
            else:
                DB::rollBack();
                return false;
            endif; 
            return true;
        } catch (\Throwable $th) {
            return true;
        }
    }
 
    public function delete($id){

        $stockTransfer   = $this->getFind($id); 
        foreach ($stockTransfer->TransferItems as $item) { 
            $variationLocation                = VariationLocationDetails::find($item->vari_loc_det_id);
            $variationLocation->qty_available = ($variationLocation->qty_available  + $item->quantity);
            $variationLocation->save();

            $TovariationLocation                = VariationLocationDetails::find($item->to_vari_loc_det_id);
            $TovariationLocation->qty_available = ($TovariationLocation->qty_available  - $item->quantity);
            $TovariationLocation->save(); 
            StockTransferItem::destroy($item->id); 
        } 

        return $this->model::destroy($id);
    } 

 
    public function VariationLocationFind($request){
        return VariationLocationDetails::where(function($query)use($request){ 
            $query->where('business_id',business_id()); 
            $query->where('branch_id',$request->branch_id);
            $query->where(function($query)use($request){
                $query->whereHas('ProductVariation',function($query)use($request){
                    $query->where('sub_sku','LIKE','%'.$request->search.'%');
                });
                $query->orWhereHas('product',function($query)use($request){
                    $query->where('name','LIKE','%'.$request->search.'%');
                    $query->orWhere('sku','LIKE','%'.$request->search.'%');
                });
            });

        })->limit(30)->get();
    }
    public function variationLocationItem($request){
        return VariationLocationDetails::with('product','ProductVariation')->find($request->variation_location_id);
    } 

    public function statusUpdate($id,$request){
        try {
            $stockTransfer         = $this->getFind($id);
            $stockTransfer->status = $request->status;
            $stockTransfer->save();
            if($request->status == StockTransferStatus::COMPLETED):
                $this->receivedProductItem($id);
            endif;
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    public function receivedProductItem($transfer_id){
        $transfer = $this->getfind($transfer_id); 
        foreach ($transfer->TransferItems as  $item) { 
            $fromVariLocDet                     = VariationLocationDetails::find($item->vari_loc_det_id);
            $fromVariLocDet->qty_available      = ($fromVariLocDet->qty_available - $item->quantity);
            $fromVariLocDet->save();

            $receivedVariLocDet                 = VariationLocationDetails::find($item->to_vari_loc_det_id);
            $receivedVariLocDet->qty_available  = ($receivedVariLocDet->qty_available + $item->quantity);
            $receivedVariLocDet->save(); 
        }
    }
 
    public function stockCheck($request){
        try {
            $stockoutofquantity = 0;
            foreach ($request->variation_locations as   $variationLocation) {
                $variationLocationFind   = VariationLocationDetails::find($variationLocation['id']);
                if($variationLocationFind->qty_available < $variationLocation['quantity']):
                    $stockoutofquantity +=1;
                endif;
            }
            if($stockoutofquantity > 0):
                return false;
            endif;
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
 

}