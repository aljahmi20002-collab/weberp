<?php
namespace Modules\AssetCategory\Repositories;

use App\Enums\Status;
use Illuminate\Support\Facades\Auth;
use Modules\AssetCategory\Entities\AssetCategory;

class AssetCategoryRepository implements AssetCategoryInterface { 
    public function get(){
        return AssetCategory::where('business_id',business_id())->orderBy('position','asc')->get();
    }
    public function getActive(){
        return AssetCategory::where('business_id',business_id())->where('status',Status::ACTIVE)->orderBy('position','asc')->get();
    }

    public function getFind($id){
        return AssetCategory::find($id);
    }

    public function store($request){
        try {
            $assetcategory               = new AssetCategory();
            $assetcategory->business_id  = business_id();
            $assetcategory->title        = $request->title;
            $assetcategory->position     = $request->position;
            $assetcategory->status       = $request->status == 'on'? Status::ACTIVE:Status::INACTIVE;
            $assetcategory->save();
            return true;
        }catch (\Exception $e) {
            return false;
        }
    }

    public function update($request)
    { 
        try {
            $assetcategory               =  AssetCategory::find($request->id);
            $assetcategory->business_id  = business_id();
            $assetcategory->title        = $request->title;
            $assetcategory->position     = $request->position;
            $assetcategory->status       = $request->status == 'on'? Status::ACTIVE:Status::INACTIVE;
            $assetcategory->save();
            return true;
        }catch (\Exception $e) {
            return false;
        }
    }

    public function delete($id){
        return AssetCategory::destroy($id);
    }

    public function statusUpdate($id){
        try {
            $assetcategory          = AssetCategory::find($id);
            $assetcategory->status  = $assetcategory->status == Status::ACTIVE ? Status::INACTIVE:Status::ACTIVE;
            $assetcategory->save();
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

}