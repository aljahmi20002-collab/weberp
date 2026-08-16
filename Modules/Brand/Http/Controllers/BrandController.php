<?php

namespace Modules\Brand\Http\Controllers;

use App\Enums\Status;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Brand\Http\Requests\StoreRequest;
use Modules\Brand\Repositories\BrandInterface;
use Modules\Business\Repositories\BusinessInterface;
use Yajra\DataTables\DataTables;

class BrandController extends Controller
{  protected $repo,$businessRepo;
    public function __construct(BrandInterface $repo,BusinessInterface $businessRepo)
    {
        $this->repo         =  $repo;
        $this->businessRepo = $businessRepo;
    }
    public function index()
    { 
        $businesses = $this->businessRepo->getAll(); 
        return view('brand::index',compact('businesses'));
    }

    public function getAllBrand(){
        $brands    = $this->repo->get();
        return DataTables::of($brands)
        ->addIndexColumn()
        ->editColumn('name',function($brand){
            return @$brand->name;
        })
        ->editColumn('logo',function($brand){
            return '<img src="'.@$brand->image .'" width="50px"/>';
        })
        ->editColumn('description',function($brand){
            return  $brand->description;
        })
        ->editColumn('position',function($brand){
            return @$brand->position;
        })
        ->editColumn('status',function($brand){
            return @$brand->my_status;
        })
        ->editColumn('action',function($brand){
            $action ='';

            if(hasPermission('brand_update') || hasPermission('brand_delete') || hasPermission('brand_status_update')):
            
                $action .= '<div class="dropdown">';
                $action .= '<a href="#" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                $action .= '<i class="fa fa-cogs"></i>';
                $action .= '</a>';
                $action .= '<div class="dropdown-menu todo-dropdown-menu" aria-labelledby="dropdownMenuButton">';
                    if(hasPermission('brand_status_update')):
                        $action .= '<a class="dropdown-item" href="'.route('brand.status.update',$brand->id) .'">';
                        $action .= $brand->status == Status::ACTIVE? '<i class="fa fa-ban"></i>':'<i class="fa fa-check"></i>';
                        $action .=  @statusUpdate($brand->status);
                        $action .= '</a>';
                    endif;

                    if(hasPermission('brand_update')):
                        $action .= '<a href="#" class="dropdown-item modalBtn"  data-bs-toggle="modal" data-url="'. route('brand.edit',$brand->id) .'" data-bs-target="#dynamic-modal" data-title="'.__('brand').' '.__('edit').'"   ><i class="fas fa-pen"></i>'. __('edit') .'</a>';
                    endif;
                    if(hasPermission('brand_delete')):
                        $action .= '<form action="'. route('brand.delete',@$brand->id) .'" method="post" class="delete-form" id="delete" data-yes='. __('yes') .' data-cancel="'. __('cancel') .'" data-title="'. __('delete_brand') .'">';
                        $action .= method_field('delete');
                        $action .= csrf_field();
                        $action .= '<button type="submit" class="dropdown-item" data-bs-toggle="tooltip" title="" >';
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
        ->rawColumns(['name','logo','description','position','status','action'])
        ->make(true);
    }
 
    public function create(){
        $businesses = $this->businessRepo->getAll(); 
        return view('brand::create',compact('businesses'));
    }
    public function store(StoreRequest $request)
    {
        if(env('DEMO')) {
            Toastr::error(__('store_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        } 
        if($this->repo->store($request)):
            Toastr::success(__('brand_added_successfully'), __('success'));
            return redirect()->route('brand.index');
        else:
            Toastr::error(__('error'), __('errors'));
            return redirect()->back();
        endif;
    }

   
 
    public function edit($id)
    {
        $businesses = $this->businessRepo->getAll();
        $brand      = $this->repo->getFind($id);
        return view('brand::edit',compact('brand','businesses'));
    }

     
    public function update(StoreRequest $request)
    {
        if(env('DEMO')) {
            Toastr::error(__('update_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }

        if($this->repo->update($request->id,$request)):
            Toastr::success(__('brand_updated_successfully'), __('success'));
            return redirect()->route('brand.index');
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
            Toastr::success(__('brand_deleted_successfully'), __('success'));
            return redirect()->route('brand.index');
        else:
            Toastr::error(__('error'), __('errors'));
            return redirect()->back();
        endif;
    }

    public function statusUpdate($id){
        if(env('DEMO')) {
            Toastr::error(__('update_system_is_disable_for_the_demo_mode'),'error');
            return redirect()->back()->withInput();
        }

        if($this->repo->statusUpdate($id)):
            Toastr::success(__('brand_updated_successfully'), __('success'));
            return redirect()->route('brand.index');
        else:
            Toastr::error(__('error'), __('errors'));
            return redirect()->back();
        endif;
    }
}
