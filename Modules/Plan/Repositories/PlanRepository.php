<?php
namespace Modules\Plan\Repositories;

use App\Enums\Status;
use App\Models\Backend\Permission;
use App\Repositories\Role\RoleInterface;
use Illuminate\Support\Facades\DB;
use Modules\Plan\Entities\Plan;
use Modules\Plan\Enums\IsDefault;
use Modules\Plan\Repositories\PlanInterface;
class PlanRepository implements PlanInterface{
    protected $planModel,$roleRepo;
    public function __construct(Plan $planModel,RoleInterface $roleRepo){
        $this->planModel = $planModel;
        $this->roleRepo = $roleRepo;
    }
    public function get(){
        return $this->planModel::orderBy('position','asc')->get();
    }
    public function getActive(){
        return $this->planModel::where('status',Status::ACTIVE)->orderBy('position','asc')->get();
    }
    public function getFind($id){
        return $this->planModel::find($id);
    }
    public function store($request){
        try { 
            $plan             = new $this->planModel(); 
            $plan->name       = $request->name;
            $plan->user_count = $request->user_count;
            $plan->days_count = $request->days_count;
            $plan->price      = $request->price;
            $plan->description= $request->description;
            $plan->options    = $request->options;
            $plan->position   = $request->position;
            $plan->modules    = $request->modules?? [];
            $plan->status     = $request->status == 'on'? Status::ACTIVE:Status::INACTIVE;
            $plan->save();

           return true;
        } catch (\Throwable $th) {
           return false;
        }
    }
    public function update($id,$request){
        try {
            $plan              = $this->planModel::find($id); 
            $plan->name        = $request->name;
            $plan->user_count  = $request->user_count;
            $plan->days_count  = $request->days_count;
            $plan->price       = $request->price;
            $plan->description = $request->description;
            $plan->options     = $request->options;
            $plan->position    = $request->position;
            $plan->modules     = $request->modules?? [];
            $plan->status      = $request->status == 'on'? Status::ACTIVE:Status::INACTIVE;
            $plan->save(); 
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
    public function delete($id){
        return $this->planModel::destroy($id);
    }
    public function statusUpdate($id){
        try {
            $plan = $this->planModel::find($id);
            $plan->status = $plan->status == Status::ACTIVE? Status::INACTIVE:Status::ACTIVE;
            $plan->save();
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    public function addDefault($id){
        try {
            $defaultPlans   = $this->planModel::where('is_default',IsDefault::YES)->get();
            foreach ($defaultPlans as $defaultPlan) {
                 $defaultPlan->is_default = IsDefault::NO;
                 $defaultPlan->save();
            }
            $plan              = $this->planModel::find($id);
            $plan->is_default  = IsDefault::YES;
            $plan->save();
            return true;
        } catch (\Throwable $th) { 
            return false;
        }
    }

    public function permissionModules(){
        $role           = $this->roleRepo->edit(2);
        $permissions    = $this->roleRepo->permissions();
        $modules        = [];
        foreach ($permissions as  $permission) {
            $count = 0; 
            foreach ($permission->keywords as  $keyword) {
                if(in_array($keyword,$role->permissions)):
                    $count += 1;
                endif;
            }
            if($count > 0):
                $modules[]  = $permission->attributes; 
            endif;
        } 
        return $modules;
    } 

}