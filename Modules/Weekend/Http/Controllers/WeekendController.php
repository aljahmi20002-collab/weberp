<?php

namespace Modules\Weekend\Http\Controllers;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Weekend\Http\Requests\UpdateRequest;
use Modules\Weekend\Repositories\WeekendInterface;
use Yajra\DataTables\DataTables;

class WeekendController extends Controller
{

   protected $repo;
    public function __construct(WeekendInterface $repo){
        $this->repo = $repo;
    }
    public function index()
    {
        return view('weekend::index');
        
    }
    
    public function getAll(){
        $weekends    = $this->repo->get();
        return DataTables::of($weekends)
        ->addIndexColumn()  
        ->editColumn('name',function($weekend){
            return @$weekend->name;
        })
        ->editColumn('weekend',function($weekend){
            return @$weekend->weekend;
        })
        ->editColumn('position',function($weekend){
            return @$weekend->position;
        })
        ->editColumn('status',function($weekend){
            return @$weekend->my_status;
        })
        ->editColumn('status_update',function($weekend){
            $statusUpdate = '';
            if(hasPermission('weekend_status_update')): 
                    $statusUpdate .= '<div class="dropdown">';
                    $statusUpdate .='<a href="#" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                    $statusUpdate .= '<i class="fa-solid fa-cogs"></i>';
                    $statusUpdate .='</a>';
                    $statusUpdate .= '<div class="dropdown-menu todo-dropdown-menu" aria-labelledby="dropdownMenuButton">';
                    $statusUpdate .= '<a class="dropdown-item" href="'. route('hrm.attendance.weekend.status.update',$weekend->id) .'">';
                    $statusUpdate .=  @statusUpdate($weekend->status);
                    $statusUpdate .= '</a>';
                    $statusUpdate .= '</div>';
                    $statusUpdate .= '</div> ';
            else:
                return '<i class="fa-solid fa-ellipsis"></i>';
            endif;

            return $statusUpdate;
        })
        ->editColumn('action',function($weekend){
            $action = '';
            if(hasPermission('weekend_update')): 
                $action .= '<div class="text-center">';
                $action .= '<a href="'. route('hrm.attendance.weekend.edit',@$weekend->id) .'" class="action-btn" data-bs-toggle="tooltip" title="'. __('edit') .'" ><i class="fas fa-pen"></i></a>';
                $action .= '</div> ';
            else:
                return '<i class="fa-solid fa-ellipsis"></i>';
            endif;
            return $action;
        })

        ->rawColumns(['name', 'weekend', 'position', 'status', 'status_update', 'action'])
        ->make(true);
    }

    public function edit($id)
    {
        $weekend   = $this->repo->getFind($id);
        return view('weekend::edit',compact('weekend'));
    }

    public function update(UpdateRequest $request)
    {
        if(env('DEMO')) {
            Toastr::error(__('update_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }
        if($this->repo->update($request->id,$request)){
            Toastr::success(__('weekend_updated_successfully'),'success');
            return redirect()->route('hrm.attendance.weekend.index');
        }else{
            Toastr::error(__('error'),__('errors'));
            return redirect()->back();
        }
    }


    public function statusUpdate($id)
    {
        if(env('DEMO')) {
            Toastr::error(__('update_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }
        if($this->repo->statusUpdate($id)){
            Toastr::success(__('weekend_updated_successfully'),__('success'));
            return redirect()->back();
        }else{
            Toastr::error(__('error'),__('errors'));
            return redirect()->back();
        }
    }

}
