<?php
namespace Modules\Assets\Repositories;

use Illuminate\Support\Facades\Auth;
use Modules\Assets\Entities\Asset;

class AssetRepository implements AssetInterface {
    public function get(){
        return Asset::where('business_id',business_id())->where(function($query){ 
                if(isUser()):
                    $query->where('branch_id',Auth::user()->branch_id); 
                endif;    
        })->orderByDesc('id')->get();
    }
    public function getFind($id){
        return Asset::find($id);
    }
    public function store($request){
   
        try {
            if(business()):
                $branch_id = $request->branch_id;
            else:
                $branch_id = Auth::user()->branch_id;
            endif;
            $asset                     = new Asset();
            $asset->business_id        = business_id();
            $asset->branch_id          = $branch_id;
            $asset->asset_category_id  = $request->asset_category_id;
            $asset->name               = $request->name;
            $asset->supplier           = $request->supplier;
            $asset->quantity           = $request->quantity;
            $asset->warranty           = $request->warranty;
            $asset->invoice_no         = $request->invoice_no;
            $asset->amount             = $request->amount;
            $asset->description        = $request->description;
            $asset->created_by         = Auth::user()->id;
            $asset->save();
            return true;
        } catch (\Throwable $th) {
            return false;
        }

    }
    public function update($request){

        try { 
            if(business()):
                $branch_id = $request->branch_id;
            else:
                $branch_id = Auth::user()->branch_id;
            endif;
            $asset                     = $this->getFind($request->id);
            $asset->business_id        = business_id();
            $asset->branch_id          = $branch_id;
            $asset->asset_category_id  = $request->asset_category_id;
            $asset->name               = $request->name;
            $asset->supplier           = $request->supplier;
            $asset->quantity           = $request->quantity;
            $asset->warranty           = $request->warranty;
            $asset->invoice_no         = $request->invoice_no;
            $asset->amount             = $request->amount;
            $asset->description        = $request->description;
            $asset->created_by         = Auth::user()->id;
            $asset->save();
            return true; 
        } catch (\Throwable $th) {
            return false;
        }
    }
    public function delete($id){
        return Asset::destroy($id);
    }
}