<?php

namespace Modules\AssetCategory\Http\Controllers;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\AssetCategory\Http\Requests\StoreRequest;
use Modules\AssetCategory\Repositories\AssetCategoryInterface;
use Yajra\DataTables\DataTables;

class AssetCategoryController extends Controller
{
    protected $repo;
    public function __construct(AssetCategoryInterface $repo)
    {
        $this->repo    = $repo;
    }
    public function index()
    {
        return view('assetcategory::index');
    }

    public function getAll(){
        $assetCategories = $this->repo->get();
        return DataTables::of($assetCategories)
        ->addIndexColumn() 
        ->editColumn('status',function($assetCategory){
            return $assetCategory->my_status;
        }) 
        ->editColumn('action',function($assetCategory){
        $action = '';
        if(hasPermission('asset_category_update') || hasPermission('asset_category_delete') || hasPermission('asset_category_status_update')):
            $action .= ' <div class="dropdown">';
            $action .= '<a href="#" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
            $action .=  '<i class="fa fa-cogs"></i>';
            $action .=  '</a>';
            $action .=  '<div class="dropdown-menu todo-dropdown-menu" aria-labelledby="dropdownMenuButton">';
                if(hasPermission('asset_category_status_update')):
                    $action .= '<a class="dropdown-item" href="'. route('settings.assetcategory.status.update',$assetCategory->id) .'">';
                    $action .=   $assetCategory->status == \App\Enums\Status::ACTIVE? '<i class="fa fa-ban"></i>':'<i class="fa fa-check"></i>';
                    $action .= @statusUpdate($assetCategory->status);
                    $action .= '</a>';
                endif;
                if(hasPermission('asset_category_update')):
                    $action .=  '<a href="#" class="dropdown-item modalBtn" data-url="'. route('settings.assetcategory.edit',$assetCategory->id) .'" data-bs-toggle="modal" data-bs-target="#dynamic-modal" data-title="'.__('asset_category').' '. __('edit') .'" ><i class="fas fa-pen" ></i>'.__('edit') .'</a>';
                endif;
                if(hasPermission('asset_category_delete')):
                    $action .=  '<form action="'. route('settings.assetcategory.delete',@$assetCategory->id) .'" method="post" class="delete-form" id="delete" data-yes='. __('yes') .' data-cancel="'. __('cancel') .'" data-title="'. __('delete_assetcategory') .'">';
                    $action .=  method_field('delete');
                    $action .=  csrf_field();
                    $action .=  '<button type="submit" class="dropdown-item "  >';
                    $action .=  '<i class="fas fa-trash-alt"></i>'. __('delete');
                    $action .= '</button>';
                    $action .=  '</form>';
                endif;  
                $action .= '</div>';
                $action .=  '</div>';   
        else:
            return '...'; 
        endif;
        return $action;
        }) 
        ->rawColumns(['status','action'])
        ->make(true);
    }
     
    public function create()
    {
        return view('assetcategory::create');
    }
 
    public function store(StoreRequest $request)
    {
        if(env('DEMO')) {
            Toastr::error(__('store_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }
        if($this->repo->store($request)){
            Toastr::success(__('asset_category_added_successfully'),__('success')); 
            return redirect()->back()->withInput(['asset_category'=>'true']);
        }else{
            Toastr::error(__('error'),__('errors'));
            return redirect()->back()->withInput(['asset_category'=>'true']);
        }
    }
 
    public function edit($id)
    {
        $assetCategory  = $this->repo->getfind($id);
        return view('assetcategory::edit',compact('assetCategory'));
    }

    
    public function update(StoreRequest $request)
    {
        if(env('DEMO')) {
            Toastr::error(__('store_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }
        if($this->repo->update($request)){
            Toastr::success(__('asset_category_updated_successfully'),__('success'));
            return redirect()->back()->withInput(['asset_category'=>'true']);
        }else{
            Toastr::error(__('error'),__('errors'));
            return redirect()->back()->withInput(['asset_category'=>'true']);
        }
    }

     
    public function destroy($id)
    {
        if(env('DEMO')) {
            Toastr::error(__('store_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }
        if($this->repo->delete($id)){
            Toastr::success(__('asset_category_deleted_successfully'),__('success'));
            return redirect()->back()->withInput(['asset_category'=>'true']);
        }else{
            Toastr::error(__('error'),__('errors'));
            return redirect()->back()->withInput(['asset_category'=>'true']);
        }
    }
    public function statusUpdate($id)
    {
        if(env('DEMO')) {
            Toastr::error(__('store_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }
        if($this->repo->statusUpdate($id)){
            Toastr::success(__('asset_category_status_update_successfully'),__('success'));
            return redirect()->back()->withInput(['asset_category'=>'true']);
        }else{
            Toastr::error(__('error'),__('errors'));
            return redirect()->back()->withInput(['asset_category'=>'true']);
        }
    }


}
