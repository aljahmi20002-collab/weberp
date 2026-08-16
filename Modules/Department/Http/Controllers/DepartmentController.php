<?php

namespace Modules\Department\Http\Controllers;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Department\Http\Requests\StoreRequest;
use Modules\Department\Repositories\DepartmentInterface;
use Yajra\DataTables\DataTables;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    protected $repo;
    public function __construct(DepartmentInterface $repo)
    {
        $this->repo  = $repo;
    }
    public function index()
    {
        return view('department::index');
    }
    
    public function getAllDepartment(){
        $departments    = $this->repo->getAllDepartment();
        return DataTables::of($departments) 
        ->addIndexColumn()
        ->editColumn('name',function($department){
            return @$department->name;
        })
        ->editColumn('position',function($department){
            return @$department->position;
        })
        ->editColumn('status',function($department){
            return @$department->my_status;
        })
        ->editColumn('action',function($department){
            $action = '';
            if(hasPermission('department_update') || hasPermission('department_delete') || hasPermission('department_status_update')):
                $action  .= '<div class="dropdown">';
                $action  .=  '<a href="#" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                $action  .= '<i class="fa fa-cogs"></i>';
                $action  .= '</a>';
                $action  .= '<div class="dropdown-menu todo-dropdown-menu" aria-labelledby="dropdownMenuButton">';
                    if(hasPermission('department_status_update')):
                        $action  .=  '<a class="dropdown-item" href="'. route('hrm.department.status.update',$department->id) .'">';
                        $action  .= $department->status == \App\Enums\Status::ACTIVE? '<i class="fa fa-ban"></i>':'<i class="fa fa-check"></i>';
                        $action  .=  @statusUpdate($department->status);
                        $action  .= '</a>';
                    endif;
                    if(hasPermission('department_update')):
                        $action  .=  '<a href="'. route('hrm.department.edit',@$department->id) .'" class="dropdown-item"   ><i class="fas fa-pen"></i>'.__('edit') .'</a>';
                    endif;
                    if(hasPermission('department_delete')):
                        $action  .=  '<form action="'. route('hrm.department.delete',@$department->id) .'" method="post" class="delete-form" id="delete" data-yes='. __('yes') .' data-cancel="'. __('cancel') .'" data-title="'. __('delete_department') .'">';
                        $action  .= method_field('delete');
                        $action  .= csrf_field();
                        $action  .= '<button type="submit" class="dropdown-item " >';
                        $action  .= '<i class="fas fa-trash-alt"></i>'. __('delete');
                        $action  .= '</button>';
                        $action  .= '</form>';
                    endif;
                    $action  .= '</div>';
                    $action  .= '</div>'; 
            else:
                return '...';
            endif;
            return $action;
        })
        ->rawColumns(['name','position','status','action'])
        ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('department::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(StoreRequest $request)
    {
        if(env('DEMO')) {
            Toastr::error(__('store_system_is_disable_for_the_demo_mode'), __('errors'));
            return redirect()->back()->withInput();
        }
        if($this->repo->store($request)){
            Toastr::success(__('department_added_successfully'), __('success'));
            return redirect()->route('hrm.department.index');
        }else{
            Toastr::error(__('error'), __('errors'));
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
        $department = $this->repo->getFind($id);
        return view('department::edit',compact('department'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(StoreRequest $request)
    {
        if(env('DEMO')) {
            Toastr::error(__('update_system_is_disable_for_the_demo_mode'), __('errors'));
            return redirect()->back()->withInput();
        }
        if($this->repo->update($request->id,$request)){
            Toastr::success(__('department_update_successfully'), __('success'));
            return redirect()->route('hrm.department.index');
        }else{
            Toastr::error(__('error'), __('errors'));
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
            Toastr::error(__('delete_system_is_disable_for_the_demo_mode'), __('errors'));
            return redirect()->back()->withInput();
        }
        if($this->repo->delete($id)){
            Toastr::success(__('department_deleted_successfully'), __('success'));
            return redirect()->back();
        }else{
            Toastr::error(__('error'), __('errors'));
            return redirect()->back();
        }
    }

    public function statusUpdate($id){
        if(env('DEMO')) {
            Toastr::error(__('update_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }
        if($this->repo->statusUpdate($id)){
            Toastr::success(__('department_updated_successfully'),__('success'));
            return redirect()->back();
        }else{
            Toastr::error(__('error'),__('errors'));
            return redirect()->back();
        }
    }
}
