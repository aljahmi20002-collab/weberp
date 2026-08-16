<?php

namespace Modules\Service\Http\Controllers;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Business\Repositories\BusinessInterface; 
use Modules\Service\Http\Requests\StoreRequest;
use Modules\Service\Repositories\ServiceInterface; 
use Yajra\DataTables\DataTables;
class ServiceController extends Controller
{
    protected $repo,$businessRepo;
    public function __construct(ServiceInterface $repo,BusinessInterface $businessRepo)
    {
        $this->repo         = $repo;
        $this->businessRepo = $businessRepo;
    }
    public function index()
    {
        
        $services    = $this->repo->get();  
        return view('service::index',compact('services'));
    }
    
    public function getAll(Request $request){
       
        $services    = $this->repo->getAll(); 
        return DataTables::of($services)
        ->addIndexColumn()
        ->editColumn('price',function($row){
            return businessCurrency($row->business_id).' '.$row->price; 
        })
        ->editColumn('status',function($row){
                return $row->my_status; 
        })
        ->addColumn('action',function($row){
            if(hasPermission('service_update') || hasPermission('service_delete') || hasPermission('service_status_update')): 
                $action  = '';   
                $action .= '<div class="dropdown">';
                $action .= '<a href="#" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                $action .=  '<i class="fa fa-cogs"></i>';
                $action .=  '</a>';
                $action .= '<div class="dropdown-menu todo-dropdown-menu" aria-labelledby="dropdownMenuButton">';

                if(hasPermission('service_status_update')):
                    $action .= '<a class="dropdown-item" href="'.route('services.status.update',$row->id).'">';
                    $action .= $row->status == \App\Enums\Status::ACTIVE? '<i class="fa fa-ban"></i>':'<i class="fa fa-check"></i>';
                    $action .= @statusUpdate($row->status);
                    $action .= '</a>';
                endif; 
                if(hasPermission('service_update')):
                    $action .= '<a href="'. route('services.edit',@$row->id).'" class="dropdown-item"   ><i class="fas fa-pen"></i>'. __('edit') .'</a>';
                endif;

                if(hasPermission('service_delete')):
                    $action .= '<form action="'.route('services.delete',@$row->id).'" method="post" class="delete-form" id="delete" data-yes="'.__('yes').'" '.'data-cancel="'. __('cancel') .'" data-title="'.__('delete_service') .'">';
                    $action .= method_field('delete');
                    $action .= csrf_field();
                    $action .= '<button type="submit" class="dropdown-item"  >';
                    $action .= '<i class="fas fa-trash-alt"></i>'.__('delete');
                    $action .= ' </button>';
                    $action .= '</form>';
                endif;
                $action .= '</div>';
                $action .= '</div>';
                return $action;  
            else:
                return '...';
            endif;
        })
        ->rawColumns(['status','action']) 
        ->make(true);
    }
    public function create()
    {
        $businesses = $this->businessRepo->getAll();
        return view('service::create',compact('businesses'));
    }
 
    public function store(StoreRequest $request)
    {
        if(env('DEMO')) {
            Toastr::error(__('store_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }

        if($this->repo->store($request)):
            Toastr::success(__('service_added_successfully'), __('success'));
            return redirect()->route('services.index');
        else:
            Toastr::error(__('error'), __('errors'));
            return redirect()->back();
        endif;
    }
 
    public function edit($id)
    {
        $businesses = $this->businessRepo->getAll();
        $service    = $this->repo->getFind($id);
        return view('service::edit',compact('businesses', 'service'));
    }
 
    public function update(Request $request)
    {
        if(env('DEMO')) {
            Toastr::error(__('update_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }

        if($this->repo->update($request->id,$request)):
            Toastr::success(__('service_updated_successfully'), __('success'));
            return redirect()->route('services.index');
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
            Toastr::success(__('service_deleted_successfully'), __('success'));
            return redirect()->route('services.index');
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
            Toastr::success(__('service_updated_successfully'), __('success'));
            return redirect()->route('services.index');
        else:
            Toastr::error(__('error'), 'error');
            return redirect()->back();
        endif;
    }

    
}
