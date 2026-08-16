<?php

namespace Modules\Variation\Http\Controllers;

use App\Enums\Status;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Business\Repositories\BusinessInterface;
use Modules\Variation\Http\Requests\StoreRequest;
use Modules\Variation\Repositories\VariationInterface;
use Yajra\DataTables\DataTables;

class VariationController extends Controller
{
    protected $repo,$businessRepo;
    public function __construct(VariationInterface $repo,BusinessInterface $businessRepo)
    {
        $this->repo           = $repo;
        $this->businessRepo   = $businessRepo;
    }
 
    public function index()
    {
      
        return view('variation::index');
    }

    public function getAll(){
        $variations   = $this->repo->get();
        return DataTables::of($variations)
        ->addIndexColumn()
        ->editColumn('name',function($variation){
            return  @$variation->name;
        })
        ->editColumn('values',function($variation){
            return  @$variation->variation_values;
        })
        ->editColumn('position',function($variation){
            return  @$variation->position;
        })
        ->editColumn('status',function($variation){
            return  @$variation->my_status;
        })
        ->editColumn('action',function($variation){
            $action = '';
        if(hasPermission('variation_update') || hasPermission('variation_delete') || hasPermission('variation_status_update')):
            
            $action .= '<div class="dropdown">';
                $action .= '<a href="#" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                $action .= '<i class="fa fa-cogs"></i>';
                $action .= '</a>';
                $action .=' <div class="dropdown-menu todo-dropdown-menu" aria-labelledby="dropdownMenuButton"> ';  
                    if(hasPermission('variation_status_update')):
                        $action .= '<a class="dropdown-item" href="'. route('variation.status.update',$variation->id).'">';
                        $action .= $variation->status ==  Status::ACTIVE? '<i class="fa fa-ban"></i>':'<i class="fa fa-check"></i>';
                        $action .= @statusUpdate($variation->status);
                        $action .= '</a>';
                    endif;

                    if(hasPermission('variation_update')):
                        $action .= '<a href="#" class="dropdown-item modalBtn"  data-bs-toggle="modal" data-url="'.route('variation.edit',$variation->id).'" data-bs-target="#dynamic-modal" data-title="Variation Edit" ><i class="fas fa-pen"></i>'. __('edit').'</a>';
                    endif;
                    if(hasPermission('variation_delete')):
                        $action .= '<form action="'.route('variation.delete',@$variation->id).'" method="post" class="delete-form" id="delete" data-yes='. __('yes').' data-cancel="'. __('cancel') .'" data-title="'. __('delete_variation').'">';
                        $action .= method_field('delete');
                        $action .= csrf_field();
                        $action .= '<button type="submit" class="dropdown-item"  >';
                        $action .= '<i class="fas fa-trash-alt"></i>'. __('delete');
                        $action .=  '</button>';
                        $action .= '</form>';
                    endif; 
                $action .= '</div>';
                $action .= '</div>'; 
        else:
            return '...';
        endif;
        return $action;

        })
        ->rawColumns(['name','values','position','status','action'])
        ->make(true);
    }
    public function create()
    {
        $businesses   = $this->businessRepo->getAll();
        return view('variation::create',compact('businesses'));
    }
 
    public function store(StoreRequest $request)
    {
        if(env('DEMO')) {
            Toastr::error(__('store_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        } 
        if($this->repo->store($request)):
            Toastr::success(__('variation_added_successfully'), __('success'));
            return redirect()->route('variation.index');
        else:
            Toastr::error(__('error'), __('errors'));
            return redirect()->back();
        endif;
    }
 
    public function edit($id)
    {
        $businesses   = $this->businessRepo->getAll();
        $variation    = $this->repo->getFind($id);
        return view('variation::edit',compact('businesses','variation'));
    }

    
    public function update(Request $request)
    {
        if(env('DEMO')) {
            Toastr::error(__('update_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        } 
        if($this->repo->update($request->id,$request)):
            Toastr::success(__('variation_updated_successfully'), __('success'));
            return redirect()->route('variation.index');
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
            Toastr::success(__('variation_deleted_successfully'), __('success'));
            return redirect()->back();
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
            Toastr::success(__('variation_updated_successfully'), __('success'));
            return redirect()->route('variation.index');
        else:
            Toastr::error(__('error'), __('errors'));
            return redirect()->back();
        endif;
    }
}
