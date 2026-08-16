<?php
namespace Modules\Brand\Repositories;

use App\Enums\Status;
use App\Repositories\Upload\UploadInterface;
use Modules\Brand\Entities\Brand;
use Modules\Brand\Repositories\BrandInterface;

class BrandRepository implements BrandInterface{
    protected $brandModel,$upload;
    public function __construct(Brand $brandModel,UploadInterface $upload)
    {
        $this->brandModel = $brandModel;
        $this->upload     = $upload;
    }
    public function get(){
        return $this->brandModel::where(function($query){
            if(!isSuperadmin()):
                $query->where('business_id',business_id());
            endif;
        })->orderBy('position')->get();
    } 
    public function getActive(){
        return $this->brandModel::where(function($query){
            if(!isSuperadmin()):
                $query->where('business_id',business_id());
            endif;
        })->where('status',Status::ACTIVE)->orderBy('position')->get();
    } 
    public function getBrands($business_id){
        return $this->brandModel::with(['business','upload'])->where(function($query) use ($business_id){
                if(isSuperadmin()):
                    $query->where('business_id',$business_id); 
                else:
                    $query->where('business_id',business_id()); 
                endif; 
        })->where('status',Status::ACTIVE)->orderBy('position')->get();
    } 

    public function getFind($id){
        return $this->brandModel::find($id);
    }
    public function store($request){
        try {
            if(isSuperadmin()):
                $business_id   = $request->business_id;
            else:
                $business_id   = business_id();
            endif;
            $brand               = new $this->brandModel();
            $brand->business_id  = $business_id;
            $brand->name         = $request->name;
            $brand->logo         = $this->upload->upload('brand','',$request->logo);
            $brand->description  = $request->description;
            $brand->position     = $request->position;
            $brand->status       = $request->status == 'on'? Status::ACTIVE:Status::INACTIVE;
            $brand->save();
            return true;
        } catch (\Throwable $th) { 
            return false;
        }
    }
    public function update($id,$request){
        try {
            if(isSuperadmin()):
                $business_id   = $request->business_id;
            else:
                $business_id   = business_id();
            endif;
            $brand               = $this->getFind($id);
            $brand->business_id  = $business_id;
            $brand->name         = $request->name;
            if($request->logo):
                $brand->logo         = $this->upload->upload('brand',$brand->logo,$request->logo);
            endif;
            $brand->description  = $request->description;
            $brand->position     = $request->position;
            $brand->status       = $request->status == 'on'? Status::ACTIVE:Status::INACTIVE;
            $brand->save();

            return true;
        } catch (\Throwable $th) {
           return false;
        }
    }
    public function delete($id){
        $brand = $this->getFind($id);
        $this->upload->unlinkImage($brand->logo);
        return $this->brandModel::destroy($id);
    }
    public function statusUpdate($id){
        try {
            $brand         = $this->getFind($id);
            $brand->status = $brand->status == Status::ACTIVE? Status::INACTIVE:Status::ACTIVE;
            $brand->save();
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
}