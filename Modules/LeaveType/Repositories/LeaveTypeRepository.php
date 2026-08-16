<?php
namespace Modules\LeaveType\Repositories;

use App\Enums\Status;
use Modules\LeaveType\Entities\LeaveType;

class LeaveTypeRepository implements LeaveTypeInterface{

    protected $LeaveTypeModel;
    public function __construct(LeaveType $LeaveTypeModel){
        $this->LeaveTypeModel  = $LeaveTypeModel;
    }
    public function get(){
        return $this->LeaveTypeModel::where('business_id',business_id())->orderBy('position', 'asc')->paginate(10);
    }
    public function getAllLeaveType(){
        return $this->LeaveTypeModel::where('business_id',business_id())->orderBy('position', 'asc')->get();
    }
    public function getActiveAll(){
        return $this->LeaveTypeModel::where(['business_id'=>business_id(),'status'=>Status::ACTIVE])->orderBy('position', 'asc')->get();
    }
    public function getFind($id){
        return $this->LeaveTypeModel::find($id);
    }
    public function store($request){
        try {
            $leaveType           = new $this->LeaveTypeModel();
            $leaveType->business_id  = business_id();
            $leaveType->name     = $request->name;
            $leaveType->position = $request->position;
            $leaveType->status   = $request->status =='on'? Status::ACTIVE : Status::INACTIVE;
            $leaveType->save();
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
    public function update($id,$request){
        try {
            $leaveType           = $this->LeaveTypeModel::find($id);
            $leaveType->business_id  = business_id();
            $leaveType->name     = $request->name; 
            $leaveType->position = $request->position;
            $leaveType->status   = $request->status =='on'? Status::ACTIVE : Status::INACTIVE;
            $leaveType->save();
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
    public function delete($id){
        return LeaveType::destroy($id);
    }
    public function statusUpdate($id){
        try {
            $leaveType = LeaveType::find($id);
            $leaveType->status = $leaveType->status == Status::ACTIVE ? Status::INACTIVE:Status::ACTIVE;
            $leaveType->save();
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
}
