<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\DateRequest;
use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Http\Resources\Api\V1\EmployeeAttendance;
use App\Http\Resources\Api\V1\TodoAssignedListResource;
use App\Http\Resources\v1\UserResource;
use App\Repositories\Role\RoleInterface;
use App\Repositories\TodoList\TodoListInterface;
use App\Repositories\User\UserInterface;
use App\Traits\ApiReturnFormatTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Modules\Branch\Http\Resources\v1\BranchResource;
use Modules\Branch\Repositories\BranchInterface;
use Modules\Business\Repositories\BusinessInterface;
use Modules\Department\Http\Resources\v1\DepartmentResource;
use Modules\Department\Repositories\DepartmentInterface;
use Modules\Designation\Http\Resources\v1\DesignationResource;
use Modules\Designation\Repositories\DesignationInterface;
use Modules\Reports\Repositories\AttendanceReport\AttendanceReportInterface;

class UserController extends Controller
{
    use ApiReturnFormatTrait;

    protected  $repo,$role,$businessRepo,$branchRepo,$todolistRepo,$attendanceReportRepo,$designationRepo,$departmentRepo;
    public function __construct(
        UserInterface $repo,
        RoleInterface $role,
        BusinessInterface $businessRepo,
        BranchInterface $branchRepo,
        DesignationInterface $designationRepo,
        DepartmentInterface $departmentRepo,
        TodoListInterface   $todolistRepo,
        AttendanceReportInterface $attendanceReportRepo
    )
    {
        $this->repo             = $repo;
        $this->role             = $role;
        $this->businessRepo     = $businessRepo;
        $this->branchRepo       = $branchRepo;
        $this->designationRepo  = $designationRepo;
        $this->departmentRepo   = $departmentRepo;
        $this->todolistRepo     = $todolistRepo;
        $this->attendanceReportRepo = $attendanceReportRepo;
    }

    public function index(){
        $users      = $this->repo->getAll();
        return $this->responseWithSuccess(__('success'),[
            'employees'=>   UserResource::collection($users)
        ],200);
    }

    public function UserTypes(){

        $userTypes  = [];
        foreach (Config::get('pos_default.user_type') as $key=>$usertype):
            if(isSuperadmin()):
                if($usertype != \App\Enums\UserType::USER):
                    $userTypes[] =(object) [
                        'id'    =>  $usertype,
                        'name'  =>  @user_type_text($usertype)
                    ]; 
                endif;
            elseif(isUser()):
                if($usertype == \App\Enums\UserType::USER):
                    $userTypes[] = (object)[
                        'id'    =>  $usertype,
                        'name'  =>  @user_type_text($usertype)
                    ]; 
                endif;
            else:
                if($usertype !== \App\Enums\UserType::SUPERADMIN):
                    $userTypes[] = (object)[
                        'id'    =>  $usertype,
                        'name'  =>  @user_type_text($usertype)
                    ]; 
                endif;
            endif;
        endforeach;

        return $userTypes;
    }

    public function create(){
 
 
        if(Auth::user()->business):
            $business_id = Auth::user()->business->id;
        elseif(Auth::user()->userBusiness):
            $business_id = Auth::user()->userBusiness->id;
        else:
            $business_id = null;
        endif;
        $branches     =  BranchResource::collection($this->branchRepo->getAll($business_id));
        $designations =  DesignationResource::collection($this->designationRepo->getActiveAll());
        $departments  =  DepartmentResource::collection($this->departmentRepo->getActiveAll()); 
       
        return $this->responseWithSuccess(__('success'),[ 
            'user_types'   => $this->UserTypes(),
            'branches'     => $branches,
            'designations' => $designations,
            'departments'  => $departments
        ],200);
    }

 
     //new employee store
     public function store(StoreRequest $request){ 
     
        if(!$this->repo->checkUserCount()):  
            return $this->responseWithError(__('user_limit_error'),[],400);
        endif; 

        if($this->repo->store($request)): 
            return $this->responseWithSuccess(__('user_added'),[],200);
        else: 
            return $this->responseWithError(__('error'),[],400);
        endif;
    }
    public function edit($id){

        $user      = new UserResource($this->repo->edit($id)); 
        if(Auth::user()->business):
            $business_id = Auth::user()->business->id;
        elseif(Auth::user()->userBusiness):
            $business_id = Auth::user()->userBusiness->id;
        else:
            $business_id = null;
        endif;
        $branches     =  BranchResource::collection($this->branchRepo->getAll($business_id));
        $designations =  DesignationResource::collection($this->designationRepo->getActiveAll());
        $departments  =  DepartmentResource::collection($this->departmentRepo->getActiveAll()); 

        return $this->responseWithSuccess(__('success'),[
            'employee'     => $user,
            'user_types'   => $this->UserTypes(), 
            'branches'     => $branches,
            'designations' => $designations,
            'departments'  => $departments
        ],200);
        
    }

    //update user
    public function update(UpdateRequest $request){ 
        if($this->repo->update($request)): 
            return $this->responseWithSuccess(__('user_updated'),[],200);
        else:
            return $this->responseWithError(__('error'),[],400);
        endif;
    }

    //delete user
    public function delete($id){
       
        if($id == 1):
            return $this->responseWithError(__('error'),[],400);
        endif;
        if($this->repo->delete($id)){ 
            return $this->responseWithSuccess(__('user_deleted'),[],200);
        }else{
            return $this->responseWithError(__('error'),[],400);
        }
    } 


    public function userView($id){
      
        $user               = $this->repo->edit($id); 
        $totalTodo          = $this->todolistRepo->totalTodo($user->id); 
        $todoLists          = $this->todolistRepo->userAllTodo($user->id);
       
        return $this->responseWithSuccess(__('success'),[
            'employee_profile'    => new UserResource($user),
            'total_todo'      => $totalTodo,
            'todo_list'       => TodoAssignedListResource::collection($todoLists)
        ],200);
    }

    public function attendance(DateRequest $request){
        $attendance  = (object) $this->attendanceReportRepo->getReport($request);
        return $this->responseWithSuccess(__('success'),[
            'attendance'=>  new EmployeeAttendance($attendance)
        ],200);
    }

}
