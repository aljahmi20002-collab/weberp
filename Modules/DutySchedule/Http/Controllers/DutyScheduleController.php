<?php

namespace Modules\DutySchedule\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\DutySchedule\Repositories\DutyScheduleInterface;
use App\Repositories\Role\RoleInterface;
use Brian2694\Toastr\Facades\Toastr;
use Modules\DutySchedule\Http\Requests\StoreRequest;
use Modules\DutySchedule\Http\Requests\UpdateRequest;
use Yajra\DataTables\DataTables;

class DutyScheduleController extends Controller
{
    protected $repo,$roleRepo;
    public function __construct(DutyScheduleInterface $repo,RoleInterface $roleRepo){
        $this->repo     = $repo;
        $this->roleRepo = $roleRepo;
    }

    public function index()
    {
        return view('dutyschedule::index');
    }
    
    public function getAllDutySchedule(){
        $duty_schedules    = $this->repo->getAllDutySchedule();
        return DataTables::of($duty_schedules)
        ->addIndexColumn() 
        ->editColumn('role',function($duty_schedule){
            return @$duty_schedule->role->name;
        })
        ->editColumn('start_time',function($duty_schedule){
            return  @\Carbon\Carbon::parse($duty_schedule->start_time)->format('h:i:s A');
        })
        ->editColumn('end_time',function($duty_schedule){
            return  @\Carbon\Carbon::parse($duty_schedule->end_time)->format('h:i:s A');
        })
        ->editColumn('action',function($duty_schedule){
            $action  = '';
            if(hasPermission('duty_schedule_update') || hasPermission('duty_schedule_delete')):
                $action .= '<div class="dropdown">';
                $action .= '<a href="#" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                $action .= '<i class="fa fa-cogs"></i>';
                $action .= '</a>';
                $action .= '<div class="dropdown-menu todo-dropdown-menu" aria-labelledby="dropdownMenuButton">';
                    if(hasPermission('duty_schedule_update')):
                    $action .='<a href="'.route('hrm.attendance.duty.schedule.edit',@$duty_schedule->id) .'" class="dropdown-item" ><i class="fas fa-pen"></i>'. __('edit') .'</a>';
                    endif;
                    if(hasPermission('duty_schedule_delete')):
                        $action .='<form action="'.route('hrm.attendance.duty.schedule.delete',@$duty_schedule->id) .'" method="post" class="delete-form" id="delete" data-yes='. __('yes') .' data-cancel="'.__('cancel') .'" data-title="'. __('delete_duty_schedule') .'">';
                        $action .= method_field('delete');
                        $action .= csrf_field();
                        $action .= '<button type="submit" class="dropdown-item" >';
                        $action .= '<i class="fas fa-trash-alt"></i>'. __('delete');
                        $action .= '</button>';
                        $action .= '</form>';
                    endif; 
                    $action .='</div>';
                    $action .=' </div>'; 
            else:
                return '<i class="fa fa-ellipsis"></i>';
            endif;
            return $action;
        }) 
        ->rawColumns(['role', 'start_time', 'end_time', 'action'])
        ->make(true);
    }
    public function create()
    {
        $roles = $this->roleRepo->all();
        return view('dutyschedule::create',compact('roles'));
    }

    public function store(StoreRequest $request)
    {
        if(env('DEMO')) {
            Toastr::error(__('store_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }
        if($this->repo->store($request)){
            Toastr::success(__('duty_schedule_added_successfully'),__('errors'));
            return redirect()->route('hrm.attendance.duty.schedule.index');
        }else{
            Toastr::error(__('error'),__('errors'));
            return redirect()->back();
        }
    }


    public function edit($id)
    {
        $duty_schedule = $this->repo->getFind($id);
        $roles = $this->roleRepo->all();
        return view('dutyschedule::edit',compact('duty_schedule','roles'));
    }

    public function update(UpdateRequest $request)
    {
        if(env('DEMO')) {
            Toastr::error(__('update_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }
        if($this->repo->update($request->id,$request)){
            Toastr::success(__('duty_schedule_updated_successfully'),__('success'));
            return redirect()->route('hrm.attendance.duty.schedule.index');
        }else{
            Toastr::error(__('error'),__('errors'));
            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        if(env('DEMO')) {
            Toastr::error(__('delete_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }
        if($this->repo->delete($id)){
            Toastr::success(__('duty_schedule_deleted_successfully'),__('success'));
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
            Toastr::success(__('duty_schedule_updated_successfully'),__('success'));
            return redirect()->back();
        }else{
            Toastr::error(__('error'),__('errors'));
            return redirect()->back();
        }
    }
}
