<?php
namespace Modules\Warranties\Repositories;

use App\Enums\Status;
use Modules\Warranties\Entities\Warranty;
use Modules\Warranties\Repositories\WarrantyInterface;
class WarrantyRepository implements WarrantyInterface{
    protected $model;
    public function __construct(Warranty $model)
    {
        $this->model = $model;
    }
    public function get(){
        return $this->model::where(function($query){
            if(!isSuperadmin()):
                $query->where('business_id',business_id());
            endif;
        })->orderBy('position','asc')->get();
    }
    public function getActive(){
        return $this->model::where(function($query){
            if(!isSuperadmin()):
                $query->where('business_id',business_id());
            endif;
        })->where('status',Status::ACTIVE)->orderBy('position','asc')->get();
    }
    public function getFind($id){
        return $this->model::find($id);
    }
    public function store($request){
        try {
            
            if(isSuperadmin()):
                $business_id   = $request->business_id;
            else:
                $business_id   = business_id();
            endif; 
            $warranty               = new $this->model();
            $warranty->business_id  = $business_id;
            $warranty->name         = $request->name;
            $warranty->description  = $request->description;
            $warranty->duration     = $request->duration;
            $warranty->duration_type= $request->duration_type;
            $warranty->position     = $request->position;
            $warranty->status       = $request->status == 'on'? Status::ACTIVE:Status::INACTIVE;
            $warranty->save();
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
            $warranty               = $this->getFind($id);
            $warranty->business_id  = $business_id;
            $warranty->name         = $request->name;
            $warranty->description  = $request->description;
            $warranty->duration     = $request->duration;
            $warranty->duration_type= $request->duration_type;
            $warranty->position     = $request->position;
            $warranty->status       = $request->status == 'on'? Status::ACTIVE:Status::INACTIVE;
            $warranty->save();
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
    public function delete($id){
        return $this->model::destroy($id);
    }
    public function statusUpdate($id){
        try {
            $warranty         = $this->getFind($id);
            $warranty->status = $warranty->status == Status::INACTIVE? Status::ACTIVE:Status::INACTIVE;
            $warranty->save();
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
}