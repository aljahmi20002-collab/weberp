<?php

namespace Modules\LeaveType\Http\Controllers;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\LeaveType\Http\Requests\StoreRequest;
use Modules\LeaveType\Http\Requests\UpdateRequest;
use Modules\LeaveType\Repositories\LeaveTypeInterface;
use Yajra\DataTables\DataTables;

class LeaveTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */

    protected $repo;
    public function __construct(LeaveTypeInterface $repo)
    {
        $this->repo = $repo;
    }
    public function index()
    {
        return view('leavetype::index');
    }
    
    public function getAllLeaveType(){
        
        $leave_types = $this->repo->getAllLeaveType();
        return DataTables::of($leave_types)
        ->addIndexColumn() 
        ->editColumn('name',function($leavetype){
            return  @$leavetype->name;
        })
        ->editColumn('position',function($leavetype){
            return  @$leavetype->position;
        })
        ->editColumn('status',function($leavetype){
            return  @$leavetype->my_status;
        })
        ->editColumn('action',function($leavetype){
            $action = '';
            if(hasPermission('leave_type_update') || hasPermission('leave_type_delete') || hasPermission('leave_type_status_update')):
            
                $action .= '<div class="dropdown">';
                $action .= '<a href="#" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                $action .='<i class="fa fa-cogs"></i>';
                $action .= '</a>';
                $action .= '<div class="dropdown-menu todo-dropdown-menu" aria-labelledby="dropdownMenuButton">';
                if(hasPermission('leave_type_status_update')):
                    $action .= '<a class="dropdown-item" href="'. route('hrm.leave.type.status.update',$leavetype->id) .'">';
                    $action .= \App\Enums\Status::ACTIVE? '<i class="fa fa-ban"></i>':'<i class="fa fa-check"></i>';
                    $action .= @statusUpdate($leavetype->status);
                    $action .= '</a>';
                endif;

                    if(hasPermission('leave_type_update')):
                        $action .= '<a href="'. route('hrm.leave.type.edit',@$leavetype->id) .'" class="dropdown-item"   ><i class="fas fa-pen"></i>'. __('edit') .'</a>';
                    endif;
                    if(hasPermission('leave_type_delete')):
                        $action .= '<form action="'. route('hrm.leave.type.delete',@$leavetype->id) .'" method="post" class="delete-form" id="delete" data-yes='. __('yes') .' data-cancel="'. __('cancel') .'" data-title="'. __('delete_leave_type') .'">';
                        $action .= method_field('delete');
                        $action .= csrf_field();
                        $action .= '<button type="submit" class="dropdown-item"   >';
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
        ->rawColumns(['name', 'position',  'status',  'action'])
        ->make(true);
        

    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('leavetype::create');
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
        if($this->repo->store($request)){
            Toastr::success(__('leave_type_added_successfully'),__('success'));
            return redirect()->route('hrm.leave.type.index');
        }else{
            Toastr::error(__('error'),__('errors'));
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
        $leaveType = $this->repo->getFind($id);
        return view('leavetype::edit',compact('leaveType'));
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
        if($this->repo->update($request->id,$request)){
            Toastr::success(__('leave_type_updated_successfully'),__('success'));
            return redirect()->route('hrm.leave.type.index');
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
            Toastr::success(__('leave_type_deleted_successfully'),__('success'));
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
            Toastr::success(__('leave_type_updated_successfully'),__('success'));
            return redirect()->back();
        }else{
            Toastr::error(__('error'),__('errors'));
            return redirect()->back();
        }
    }
}
