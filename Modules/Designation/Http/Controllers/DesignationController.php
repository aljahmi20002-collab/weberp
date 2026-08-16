<?php

namespace Modules\Designation\Http\Controllers;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Designation\Http\Requests\StoreRequest;
use Modules\Designation\Repositories\DesignationInterface;
use Yajra\DataTables\DataTables;

class DesignationController extends Controller
{
    protected $repo;
    public function __construct(DesignationInterface $repo)
    {
        $this->repo = $repo;
    }
    public function index()
    {
        
        return view('designation::index' );
    }

    public function getAllDesignation(){
        $designations      = $this->repo->getAllDesignations();
        return DataTables::of($designations) 
        ->addIndexColumn()
        ->editColumn('name',function($designation){
            return  @$designation->name;
        })
        ->editColumn('position',function($designation){
            return @$designation->position;
        })
        ->editColumn('status',function($designation){
            return @$designation->my_status;
        })
        ->editColumn('action',function($designation){
            $action  = '';
            if(hasPermission('designation_update') ||
            hasPermission('designation_delete') ||
            hasPermission('designation_status_update')):
                    $action .=  '<div class="dropdown">';
                    $action .= '<a href="#" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                    $action .= ' <i class="fa fa-cogs"></i>';
                    $action .= '</a>';
                    $action .= '<div class="dropdown-menu todo-dropdown-menu" aria-labelledby="dropdownMenuButton">';
                        if(hasPermission('designation_status_update')):
                            $action .= '<a class="dropdown-item" href="'. route('hrm.designation.status.update',$designation->id) .'">';
                            $action .=  $designation->status == \App\Enums\Status::ACTIVE? '<i class="fa fa-ban"></i>':'<i class="fa fa-check"></i>';
                            $action .=  @statusUpdate($designation->status);
                            $action .= '</a>';
                        endif;

                        if(hasPermission('designation_update')):
                            $action .= '<a href="'. route('hrm.designation.edit',@$designation->id) .'" class="dropdown-item"  ><i class="fas fa-pen"></i>'. __('edit') .'</a>';
                        endif;
                        if(hasPermission('designation_delete')):
                            $action .=  '<form action="'. route('hrm.designation.delete',@$designation->id) .'" method="post" class="delete-form" id="delete" data-yes='. __('yes') .' '. 'data-cancel="'. __('cancel') .'" data-title="'. __('delete_designation') .'">';
                            $action .= method_field('delete');
                            $action .= csrf_field();
                            $action .= '<button type="submit" class="dropdown-item" >';
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
        ->rawColumns(['name','position','status','action'])
        ->make(true);
    }

    public function create()
    {
        return view('designation::create');
    }

    public function store(StoreRequest $request)
    {
        if(env('DEMO')) {
            Toastr::error(__('store_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }
        if($this->repo->store($request)){
            Toastr::success(__('designation_added_successfully'),__('success'));
            return redirect()->route('hrm.designation.index');
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
        $designation   = $this->repo->getFind($id);
        return view('designation::edit',compact('designation'));
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
            Toastr::error(__('update_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }
        if($this->repo->update($request->id,$request)){
            Toastr::success(__('designation_updated_successfully'),__('success'));
            return redirect()->route('hrm.designation.index');
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
            Toastr::success(__('designation_deleted_successfully'),__('success'));
            return redirect()->back();
        }else{
            Toastr::error(__('error'),__('errors'));
            return redirect()->back();
        }
    }

    public function statusUpdate($id){
        if(env('DEMO')) {
            Toastr::error(__('update_system_is_disable_for_the_demo_mode'),);
            return redirect()->back()->withInput();
        }
        if($this->repo->statusUpdate($id)){
            Toastr::success(__('designation_updated_successfully'),__('success'));
            return redirect()->back();
        }else{
            Toastr::error(__('error'),__('errors'));
            return redirect()->back();
        }
    }
}
