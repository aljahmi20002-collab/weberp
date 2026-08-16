<?php
namespace Modules\Weekend\Repositories;

use App\Enums\Status;
use Modules\Weekend\Repositories\WeekendInterface;
use Modules\Weekend\Entities\Weekend;

class WeekendRepository implements WeekendInterface{
    protected $weekendModel;
    public function __construct(Weekend $weekendModel)
    {
        $this->weekendModel = $weekendModel;
    }
    public function get(){
        return $this->weekendModel::orderBy('position','asc')->get();
    }
    public function getFind($id){
        return $this->weekendModel::find($id);
    }
    public function update($id,$request){
        try {
            $weekend                = $this->weekendModel->find($id);
            $weekend->name          = $request->name;
            $weekend->is_weekend    = (int) $request->weekend;
            $weekend->position      = $request->position;
            $weekend->status        = $request->status == 'on'? Status::ACTIVE : Status::INACTIVE;
            $weekend->save();
            return true;
        } catch (\Throwable $th) {
           return false;
        }
    }
    public function statusUpdate($id){
        try {
            $weekend           = $this->weekendModel::find($id);
            $weekend->status   = $weekend->status == Status::ACTIVE ? Status::INACTIVE : Status::ACTIVE;
            $weekend->save();
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
}
