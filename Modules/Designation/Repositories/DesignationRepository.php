<?php
namespace Modules\Designation\Repositories;

use App\Enums\Status;
use Modules\Designation\Entities\Designation;
use Modules\Designation\Repositories\DesignationInterface;
class DesignationRepository implements DesignationInterface
{
    protected $designationModel;
    public function __construct(Designation $designationModel){
        $this->designationModel = $designationModel;
    }
    public function get(){
        return $this->designationModel::orderBy('position','asc')->paginate(10);
    }
    public function getAllDesignations(){
        return $this->designationModel::orderBy('position','asc')->get();
    }
    public function getActiveAll(){
        return $this->designationModel::where('status',Status::ACTIVE)->orderBy('position','asc')->get();
    }
    public function getFind($id){
        return $this->designationModel::find($id);
    }
    public function store($request){
        try {
            $designation            = new $this->designationModel();
            $designation->name      = $request->name;
            $designation->position  = $request->position;
            $designation->status    = $request->status == 'on'? Status::ACTIVE:Status::INACTIVE;
            $designation->save();

            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
    public function update($id,$request){
        try {
            $designation           =  $this->designationModel::find($id);
            $designation->name     = $request->name;
            $designation->position = $request->position;
            $designation->status   = $request->status == 'on'? Status::ACTIVE:Status::INACTIVE;
            $designation->save();
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
    public function delete($id){
        return $this->designationModel::destroy($id);
    }
    public function statusUpdate($id){
        try {
           $designation         = $this->designationModel::find($id);
           $designation->status = $designation->status == Status::ACTIVE ? Status::INACTIVE:Status::ACTIVE;
           $designation->save();
           return true;
        } catch (\Throwable $th) {
           return false;
        }
    }
}
