<?php
namespace Modules\LeaveAssign\Repositories;

use App\Enums\Status;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Modules\LeaveAssign\Entities\LeaveAssign;
use Modules\LeaveAssign\Repositories\LeaveAssignInterface;

class LeaveAssignRepository implements LeaveAssignInterface{
    protected $leaveAssignModel;
    public function __construct(LeaveAssign $leaveAssignModel){
        $this->leaveAssignModel = $leaveAssignModel;
    }
    public function get(){
        return $this->leaveAssignModel::where(function($query){
            if(business()):
                $query->where(['business_id'=>business_id()]);
            else:
                $query->where('business_id',business_id());
                $query->where('role_id',Auth::user()->role_id);
            endif;
        })->whereYear('created_at',Date('Y'))->orderByDesc('id')->paginate(10);
    }
    public function getAllLeaveAssign(){
        return $this->leaveAssignModel::where(function($query){
            if(business()):
                $query->where(['business_id'=>business_id()]);
            else:
                $query->where('business_id',business_id());
                $query->where('role_id',Auth::user()->role_id);
            endif;
        })->whereYear('created_at',Date('Y'))->orderByDesc('id')->get();
    }
    public function existsAssigned($request){
        return $this->leaveAssignModel::where(['business_id'=>business_id(),'role_id'=>$request->role_id,'type_id'=>$request->type_id])->whereYear('created_at',Date('Y'))->first();
    }

    public function leaveAssign(){
        return $this->leaveAssignModel::where(['business_id'=>business_id(),'role_id'=>Auth::user()->role_id,'status'=>Status::ACTIVE])->whereYear('created_at',Date('Y'))->get();
    }
    public function AssignedLeave($employee_id){
        $user = User::find($employee_id);
        return $this->leaveAssignModel::where(['business_id'=>$user->business->id,'role_id'=>$user->role_id,'status'=>Status::ACTIVE])->whereYear('created_at',Date('Y'))->get();
    }
    public function leaveAssignWithPaginate(){
        return $this->leaveAssignModel::where(['business_id'=>business_id(),'role_id'=>Auth::user()->role_id,'status'=>Status::ACTIVE])->whereYear('created_at',Date('Y'))->paginate(10);
    }
    public function getleaveAssignWithPaginate(){
        return $this->leaveAssignModel::where(['business_id'=>business_id(),'role_id'=>Auth::user()->role_id,'status'=>Status::ACTIVE])->whereYear('created_at',Date('Y'))->get();
    }

    public function getFind($id){
        return $this->leaveAssignModel::find($id);
    }
    public function store($request){
        try {
            $leaveAssign            = new $this->leaveAssignModel();
            $leaveAssign->business_id   = business_id();
            $leaveAssign->role_id   = $request->role_id;
            $leaveAssign->type_id   = $request->type_id;
            $leaveAssign->days      = $request->days;
            $leaveAssign->status    = $request->status == 'on'? Status::ACTIVE : Status::INACTIVE;
            $leaveAssign->save();
            return true;
        } catch (\Throwable $th) {
           return false;
        }
    }
    public function update($id,$request){
      try {
        $leaveAssign            = $this->leaveAssignModel::find($id);
        $leaveAssign->business_id   = business_id();
        $leaveAssign->role_id   = $request->role_id;
        $leaveAssign->type_id   = $request->type_id;
        $leaveAssign->days      = $request->days;
        $leaveAssign->status    = $request->status == 'on'? Status::ACTIVE : Status::INACTIVE;
        $leaveAssign->save();
        return true;
      } catch (\Throwable $th) {
        return false;
      }
    }
    public function delete($id){
      return $this->leaveAssignModel::destroy($id);
    }
    public function statusUpdate($id){
        try {
            $leaveAssign         = $this->leaveAssignModel::find($id);
            $leaveAssign->status = $leaveAssign->status == Status::ACTIVE ? Status::INACTIVE:Status::ACTIVE;
            $leaveAssign->save();
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
}
