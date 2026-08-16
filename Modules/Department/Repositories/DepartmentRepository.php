<?php
namespace Modules\Department\Repositories;

use App\Enums\Status;
use Modules\Department\Entities\Department;
use Modules\Department\Repositories\DepartmentInterface;
class DepartmentRepository implements DepartmentInterface{
    protected $departmentModel;
    public function __construct(Department $departmentModel){
        $this->departmentModel = $departmentModel;
    }
    public function get(){
        return $this->departmentModel::orderBy('position','asc')->paginate(10);
    }
    public function getAllDepartment(){
        return $this->departmentModel::orderBy('position','asc')->get();
    }
    public function getActiveAll(){
        return $this->departmentModel::active()->orderBy('position','asc')->get();
    }
    public function getFind($id){
        return $this->departmentModel::find($id);
    }
    public function store($request){
        try {
            $department           = new $this->departmentModel();
            $department->name     = $request->name;
            $department->position = $request->position;
            $department->status   = $request->status == 'on'? Status::ACTIVE:Status::INACTIVE;
            $department->save();
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
    public function update($id,$request){
        try {
            $department           = $this->departmentModel::find($id);
            $department->name     = $request->name;
            $department->position = $request->position;
            $department->status   = $request->status == 'on'? Status::ACTIVE:Status::INACTIVE;
            $department->save();
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
    public function delete($id){
        return $this->departmentModel::destroy($id);
    }
    public function statusUpdate($id){
         try {
             $department         = $this->departmentModel::find($id);
             $department->status = $department->status == Status::ACTIVE ? Status::INACTIVE : Status::ACTIVE;
             $department->save();
            return true;
         } catch (\Throwable $th) {
            return false;
         }
    }
}
