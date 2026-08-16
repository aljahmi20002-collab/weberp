<?php
namespace Modules\DutySchedule\Repositories;
use  Modules\DutySchedule\Repositories\DutyScheduleInterface;
use App\Enums\Status;
use Modules\DutySchedule\Entities\DutySchedule;
class DutyScheduleRepository implements DutyScheduleInterface{
    public function get(){
        return DutySchedule::orderByDesc('id')->paginate(10);
    }
    public function getAllDutySchedule(){
        return DutySchedule::orderByDesc('id')->get();
    }
    public function getActiveAll(){
        return DutySchedule::orderByDesc('id')->all();
    }
    public function getFind($id){
        return DutySchedule::find($id);
    }
    public function store($request){
        try { 
            $dutySchedule             = new DutySchedule();
            $dutySchedule->role_id    = $request->role_id;
            $dutySchedule->start_time = $request->start_time;
            $dutySchedule->end_time   = $request->end_time;
            $dutySchedule->save();
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
    public function update($id,$request){
        try {
            $dutySchedule             = DutySchedule::find($id);
            $dutySchedule->role_id    = $request->role_id;
            $dutySchedule->start_time = $request->start_time;
            $dutySchedule->end_time   = $request->end_time;
            $dutySchedule->save();
            return true;
        } catch (\Throwable $th) {
           return false;
        }
    }
    public function delete($id){
        return DutySchedule::destroy($id);
    }

}
