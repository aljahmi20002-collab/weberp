<?php

namespace Modules\Warranties\Http\Controllers;

use App\Enums\Status;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Business\Repositories\BusinessInterface;
use Modules\Warranties\Http\Requests\StoreRequest;
use Modules\Warranties\Repositories\WarrantyInterface;
use Yajra\DataTables\DataTables;

class WarrantiesController extends Controller
{
    protected $repo,$businessRepo;
    public function __construct(WarrantyInterface $repo,BusinessInterface $businessRepo)
    {
        $this->repo = $repo;
        $this->businessRepo = $businessRepo;
    }
    public function index()
    {  
        return view('warranties::index');
    }

    public function getAll(){
        $warranties   = $this->repo->get();
        return DataTables::of($warranties)
        ->addIndexColumn() 
        ->editColumn('name',function($warranty){
            return @$warranty->name;
        })
        ->editColumn('duration',function($warranty){
            return   $warranty->duration;
        })
        ->editColumn('duration_type',function($warranty){
            return $warranty->durationtypes;
        })
        ->editColumn('description',function($warranty){
            return  $warranty->description;
        })
        ->editColumn('position',function($warranty){
            return @$warranty->position;
        })
        ->editColumn('status',function($warranty){
            return @$warranty->my_status;
        })
        ->editColumn('action',function($warranty){
            $action = '';
            if(hasPermission('warranty_update') || hasPermission('warranty_delete') || hasPermission('warranty_status_update')):
                
                $action .=  '<div class="dropdown">';
                $action .=  '<a href="#" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                $action .= '<i class="fa fa-cogs"></i>';
                $action .= '</a>';
                $action .=  '<div class="dropdown-menu todo-dropdown-menu" aria-labelledby="dropdownMenuButton">';
                    
                    if(hasPermission('warranty_status_update')):
                        $action .= '<a class="dropdown-item" href="'. route('warranty.status.update',$warranty->id) .'">';
                        $action .= $warranty->status ==  Status::ACTIVE? '<i class="fa fa-ban"></i>':'<i class="fa fa-check"></i>';
                        $action .=  @statusUpdate($warranty->status);
                        $action .=  '</a>';
                    endif;

                    if(hasPermission('warranty_update')):
                        $action .= '<a href="#" class="dropdown-item modalBtn"  data-bs-toggle="modal" data-url="'. route('warranty.edit',$warranty->id).'" data-title="'.__('warranty').' '.__('edit').'" data-bs-target="#dynamic-modal"   ><i class="fas fa-pen"></i>'. __('edit') .'</a>';
                    endif;

                    if(hasPermission('warranty_delete')):
                        $action .=  '<form action="'.route('warranty.delete',@$warranty->id) .'" method="post" class="delete-form" id="delete" data-yes='. __('yes') .' data-cancel="'. __('cancel') .'" data-title="'.__('delete_warranty') .'">';
                        $action .=  method_field('delete');
                        $action .= csrf_field();
                        $action .= '<button type="submit" class="dropdown-item" >';
                        $action .= '<i class="fas fa-trash-alt"></i>'.__('delete');
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
        ->rawColumns(['name','duration','duration_type','description','position','status','action'])
        ->make(true);
    }
 
    public function create()
    {
        $businesses   = $this->businessRepo->getAll();
        return view('warranties::create',compact('businesses'));
    }
 
    public function store(StoreRequest $request)
    {
        if(env('DEMO')) {
            Toastr::error(__('store_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        } 
        if($this->repo->store($request)):
            Toastr::success(__('warranty_added_successfully'), __('success'));
            return redirect()->route('warranty.index');
        else:
            Toastr::error(__('error'), __('errors'));
            return redirect()->back();
        endif;
    }

 

   
    public function edit($id)
    {
        $businesses   = $this->businessRepo->getAll();
        $warranty     = $this->repo->getFind($id);
        return view('warranties::edit',compact('businesses','warranty'));
    }

   
    public function update(StoreRequest $request)
    {
        if(env('DEMO')) {
            Toastr::error(__('update_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        } 
        if($this->repo->update($request->id,$request)):
            Toastr::success(__('warranty_updated_successfully'), __('success'));
            return redirect()->route('warranty.index');
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
            Toastr::success(__('warranty_deleted_successfully'), __('success'));
            return redirect()->route('warranty.index');
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
            Toastr::success(__('warranty_updated_successfully'), __('success'));
            return redirect()->route('warranty.index');
        else:
            Toastr::error(__('error'), __('errors'));
            return redirect()->back();
        endif;
    }
}
