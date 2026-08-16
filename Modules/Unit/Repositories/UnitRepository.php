<?php
namespace Modules\Unit\Repositories;

use App\Enums\Status;
use Modules\Unit\Entities\Unit;
use Modules\Unit\Repositories\UnitInterface;
class UnitRepository implements UnitInterface {
    protected $model;
    public function __construct(Unit $model)
    {
        $this->model   = $model;
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
           $unit               = new $this->model();
           $unit->business_id  = $business_id;
           $unit->name         = $request->name;
           $unit->short_name   = $request->short_name;
           $unit->position     = $request->position;
           $unit->status       = $request->status =='on'? Status::ACTIVE:Status::INACTIVE;
           $unit->save();
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
           $unit               = $this->getFind($id);
           $unit->business_id  = $business_id;
           $unit->name         = $request->name;
           $unit->short_name   = $request->short_name;
           $unit->position     = $request->position;
           $unit->status       = $request->status == 'on'? Status::ACTIVE:Status::INACTIVE;
           $unit->save();
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
            $unit           = $this->getFind($id);
            $unit->status   = $unit->status == Status::INACTIVE ? Status::ACTIVE:Status::INACTIVE;
            $unit->save();
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    public function getUnits($business_id){
        return $this->model::where(function($query)use($business_id){
            if(isSuperadmin()):
                $query->where('business_id',$business_id);
            else:
                $query->where('business_id',business_id());
            endif;
        })->where('status',Status::ACTIVE)->orderBy('position','asc')->get();
    }

}