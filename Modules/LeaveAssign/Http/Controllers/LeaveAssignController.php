<?php

namespace Modules\LeaveAssign\Http\Controllers;

use App\Repositories\Role\RoleInterface;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\LeaveAssign\Entities\LeaveAssign;
use Modules\LeaveAssign\Http\Requests\StoreRequest;
use Modules\LeaveAssign\Http\Requests\UpdateRequest;
use Modules\LeaveAssign\Repositories\LeaveAssignInterface;
use Modules\LeaveType\Repositories\LeaveTypeInterface;
use Yajra\DataTables\DataTables;

class LeaveAssignController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    protected $repo,$roleRepo,$LeaveTypeRepo;
    public function __construct(LeaveAssignInterface $repo, RoleInterface $roleRepo, LeaveTypeInterface $LeaveTypeRepo)
    {
        $this->repo          = $repo;
        $this->roleRepo      = $roleRepo;
        $this->LeaveTypeRepo = $LeaveTypeRepo;
    }
    public function index()
    {
        return view('leaveassign::index');
    }
    
    public function getAllLeaveAssign(){ 
        $leave_assigns    = $this->repo->getAllLeaveAssign();
        return DataTables::of($leave_assigns)
        ->addIndexColumn() 
        ->editColumn('leave_type',function($leave_assign){
            return @$leave_assign->leavetype->name;
        })
        ->editColumn('role',function($leave_assign){
            return @$leave_assign->role->name;
        })
        ->editColumn('days',function($leave_assign){
            return @$leave_assign->days;
        })
        ->editColumn('status',function($leave_assign){
            return @$leave_assign->my_status;
        })
        ->editColumn('action',function($leave_assign){
            $action   = '';
            if(hasPermission('leave_assign_update') || hasPermission('leave_assign_delete') || hasPermission('leave_assign_status_update')):
                $action .= '<div class="dropdown">';
                $action .= '<a href="#" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                $action .= '<i class="fa fa-cogs"></i>';
                $action .= '</a>';
                $action .= '<div class="dropdown-menu todo-dropdown-menu" aria-labelledby="dropdownMenuButton">';
                    if(hasPermission('leave_assign_status_update')):
                        $action .=  '<a class="dropdown-item" href="'. route('hrm.leave.assign.status.update',$leave_assign->id) .'">';
                        $action .=  $leave_assign->status == \App\Enums\Status::ACTIVE? '<i class="fa fa-ban"></i>':'<i class="fa fa-check"></i>' ;
                        $action .=  @statusUpdate($leave_assign->status);
                        $action .= '</a>'; 
                    endif;
                    if(hasPermission('leave_assign_update')):
                        $action .=  '<a href="'.route('hrm.leave.assign.edit',@$leave_assign->id) .'" class="dropdown-item" ><i class="fas fa-pen"></i>'. __('edit') .'</a>';
                    endif;
                    if(hasPermission('leave_assign_delete')):
                        $action .=  '<form action="'. route('hrm.leave.assign.delete',@$leave_assign->id) .'" method="post" class="delete-form" id="delete" data-yes='. __('yes') .' data-cancel="'. __('cancel') .'" data-title="'. __('delete_leave_assign') .'">';
                        $action .= method_field('delete');
                        $action .= csrf_field();
                        $action .= '<button type="submit" class="dropdown-item"  >';
                        $action .= '<i class="fas fa-trash-alt"></i>'. __('delete');
                        $action .= '</button>';
                        $action .= '</form>';
                    endif;
                    $action .= '</div>';
                    $action .= '</div>';
            else:
                return '...';
            endif;
            return $action;
        })

        ->rawColumns(['leave_type','role','days','status','action' ])
        ->make(true);

    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $roles       = $this->roleRepo->all();
        $leave_types = $this->LeaveTypeRepo->getActiveAll();
        return view('leaveassign::create',compact('roles','leave_types'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(StoreRequest $request)
    {
        if(env('DEMO')) {
            Toastr::error(__('store_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }
        $check   = LeaveAssign::where(['business_id'=>business_id(),'role_id'=>$request->role_id,'type_id'=>$request->type_id])->whereYear('created_at',Date('Y'))->first();
        if($check):
            Toastr::error(__('leave_type_already_assigned'),__('errors'));
            return redirect()->back()->withInput();
        endif;
        if($this->repo->store($request)){
            Toastr::success(__('leave_assign_added_successfully'),__('success'));
            return redirect()->route('hrm.leave.assign.index');
        }else{
            Toastr::error(__('error'),'error');
            return redirect()->back();
        }
    }


    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $roles         = $this->roleRepo->all();
        $leave_types   = $this->LeaveTypeRepo->getActiveAll();
        $leave_assign  = $this->repo->getFind($id);
        return view('leaveassign::edit',compact('leave_assign','roles','leave_types'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(UpdateRequest $request)
    {
        if(env('DEMO')) {
            Toastr::error(__('update_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }
        $findExistsAssign  = $this->repo->existsAssigned($request);
        if($findExistsAssign && $findExistsAssign->id != $request->id):
            Toastr::error(__('leave_type_already_assigned'),__('errors'));
            return redirect()->back()->withInput();
        endif;
        if($this->repo->update($request->id,$request)){
            Toastr::success(__('leave_assign_updated_successfully'),__('success'));
            return redirect()->route('hrm.leave.assign.index');
        }else{
            Toastr::error(__('error'),__('errors'));
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if(env('DEMO')) {
            Toastr::error(__('delete_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }
        if($this->repo->delete($id)){
            Toastr::success(__('leave_assign_delete_successfully'),__('success'));
            return redirect()->back();
        }else{
            Toastr::error(__('error'),__('errors'));
            return redirect()->back();
        }
    }

    public function statusUpdate($id){
        if(env('DEMO')) {
            Toastr::error(__('update_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }
        if($this->repo->statusUpdate($id)){
            Toastr::success(__('leave_assign_updated_successfully'),__('success'));
            return redirect()->route('hrm.leave.assign.index');
        }else{
            Toastr::error(__('error'),__('errors'));
            return redirect()->back();
        }
    }
}
