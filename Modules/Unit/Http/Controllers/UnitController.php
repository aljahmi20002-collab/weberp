<?php

namespace Modules\Unit\Http\Controllers;

use App\Enums\Status;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Business\Repositories\BusinessInterface;
use Modules\Unit\Http\Requests\StoreRequest;
use Modules\Unit\Repositories\UnitInterface;
use Yajra\DataTables\DataTables;

class UnitController extends Controller
{
    protected $repo,$businessRepo;
    public function __construct(UnitInterface $repo,BusinessInterface $businessRepo)
    {
        $this->repo           = $repo;
        $this->businessRepo   = $businessRepo;
    }
    public function index()
    { 
        return view('unit::index');
    }
 
    public function getAll(){
        $units    = $this->repo->get();
        return DataTables::of($units)
        ->addIndexColumn() 
        ->editColumn('name',function($unit){
            return  @$unit->name;
        })
        ->editColumn('short_name',function($unit){
            return  $unit->short_name;
        })
        ->editColumn('position',function($unit){
            return @$unit->position;
        })
        ->editColumn('status',function($unit){
            return @$unit->my_status;
        })
        ->editColumn('action',function($unit){
        $action = '';
        
        if(hasPermission('unit_update') || hasPermission('unit_delete') || hasPermission('unit_status_update')):
            
            $action .= '<div class="dropdown">';
            $action .= '<a href="#" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
            $action .= '<i class="fa fa-cogs"></i>';
            $action .= '</a>';
            $action .= '<div class="dropdown-menu todo-dropdown-menu" aria-labelledby="dropdownMenuButton">';
                    
                if(hasPermission('unit_status_update')):
                    $action .= '<a class="dropdown-item" href="'.route('units.status.update',$unit->id).'">';
                    $action .= $unit->status ==  Status::ACTIVE? '<i class="fa fa-ban"></i>':'<i class="fa fa-check"></i>';
                    $action .=  @statusUpdate($unit->status);
                    $action .= '</a>';
                endif;
                
                if(hasPermission('unit_update')):
                    $action .= '<a href="#" class="dropdown-item modalBtn"  data-modalsize="modal-lg" data-bs-toggle="modal" data-url="'.route('units.edit',$unit->id) .'" data-bs-target="#dynamic-modal" data-title="'.__('unit').' '.__('edit').'"  > <i class="fas fa-pen"></i>'.__('edit') .'</a>';
                endif;
                if(hasPermission('unit_delete')):
                    $action .= '<form action="'. route('units.delete',@$unit->id) .'" method="post" class="delete-form" id="delete"  data-yes='. __('yes') .' data-cancel="'.__('cancel') .'" data-title="'.__('delete_unit') .'">';
                    $action .= method_field('delete');
                    $action .= csrf_field();
                    $action .= '<button type="submit" class="dropdown-item " >';
                    $action .= '<i class="fas fa-trash-alt"></i>'.__('delete');
                    $action .= '</button>';
                    $action .= '</form>';
                endif;
                $action .= '</div>';
                $action .= ' </div>';
        else:
            return '...';
        endif;

        return $action;
        })
        ->rawColumns(['name','short_name','position','status','action'])
        ->make(true);
    }
    public function create()
    {
        $businesses = $this->businessRepo->get();
        return view('unit::create',compact('businesses'));
    }
 
    public function store(StoreRequest $request)
    {
        if(env('DEMO')) {
            Toastr::error(__('store_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        } 
        if($this->repo->store($request)):
            Toastr::success(__('unit_added_successfully'), __('success'));
            return redirect()->route('units.index');
        else:
            Toastr::error(__('error'), __('errors'));
            return redirect()->back();
        endif;
    }
 
    public function edit($id)
    {
        $unit       = $this->repo->getFind($id);
        $businesses = $this->businessRepo->get();
        return view('unit::edit',compact('unit','businesses'));
    }
 
    public function update(StoreRequest $request)
    {
        if(env('DEMO')) {
            Toastr::error(__('update_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        } 
        if($this->repo->update($request->id,$request)):
            Toastr::success(__('unit_updated_successfully'), __('success'));
            return redirect()->route('units.index');
        else:
            Toastr::error(__('error'), __('errors'));
            return redirect()->back();
        endif;
    }
 
    public function destroy($id)
    {
        if(env('DEMO')) {
            Toastr::error(__('delete_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        } 
        if($this->repo->delete($id)):
            Toastr::success(__('unit_deleted_successfully'), __('success'));
            return redirect()->route('units.index');
        else:
            Toastr::error(__('error'), __('errors'));
            return redirect()->back();
        endif;
    }

    public function statusUpdate($id){
        if(env('DEMO')) {
            Toastr::error(__('update_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        } 
        if($this->repo->statusUpdate($id)):
            Toastr::success(__('unit_updated_successfully'), __('success'));
            return redirect()->route('units.index');
        else:
            Toastr::error(__('error'), __('errors'));
            return redirect()->back();
        endif;
    }
}
