<?php
namespace Modules\Variation\Repositories;

use App\Enums\Status;
use Modules\Variation\Entities\Variation;
use Modules\Variation\Repositories\VariationInterface;
class VariationRepository implements VariationInterface{
    protected $model;
    public function __construct(Variation $model)
    {
        $this->model   = $model;
    }
    public function get(){
        return $this->model::where(function($query){
            if(!isSuperadmin()):
                $query->where('business_id',business_id());
            endif;
        })->get();
    }
    public function getNameFind($request){
         $v_values =  $this->model::where('business_id',business_id())->where('name',$request->name)->first();
         return $v_values == null? '':$v_values->value;
    }
    public function getFind($id){
        return $this->model::find($id);
    }
    public function store($request){
        try {
            if(isSuperadmin()):
                $business_id  = $request->business_id;
            else:
                $business_id  = business_id();
            endif;
            $varation              = new $this->model();
            $varation->business_id = $business_id;
            $varation->name        = $request->name;
            $varation->value       = $request->values;
            $varation->position    = $request->position;
            $varation->status      = $request->status == 'on'? Status::ACTIVE:Status::INACTIVE;
            $varation->save();
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
    public function update($id,$request){
        try {

            if(isSuperadmin()):
                $business_id  = $request->business_id;
            else:
                $business_id  = business_id();
            endif;
            $varation              = $this->getFind($id);
            $varation->business_id = $business_id;
            $varation->name        = $request->name;
            $varation->value       = $request->values;
            $varation->position    = $request->position;
            $varation->status      = $request->status == 'on'? Status::ACTIVE:Status::INACTIVE;
            $varation->save();

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
            $varation         = $this->getFind($id);
            $varation->status = $varation->status == Status::INACTIVE? Status::ACTIVE:Status::INACTIVE;
            $varation->save();
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    public function getVariation($business_id){
        return $this->model::where(function($query) use ($business_id){
            if(isSuperadmin()):
                $query->where('business_id',$business_id);
            else:
                $query->where('business_id',business_id());
            endif;
        })->where('status',Status::ACTIVE)->orderBy('position','asc')->get();
    }
}