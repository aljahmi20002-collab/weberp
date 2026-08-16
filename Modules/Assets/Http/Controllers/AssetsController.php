<?php

namespace Modules\Assets\Http\Controllers;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\AssetCategory\Repositories\AssetCategoryInterface;
use Modules\Assets\Http\Requests\StoreRequest;
use Modules\Assets\Repositories\AssetInterface;
use Modules\Branch\Repositories\BranchInterface;
use Yajra\DataTables\DataTables;

class AssetsController extends Controller
{
    protected $repo,$assetCategoryRepo,$branchRepo;
    public function __construct(
            AssetInterface $repo,
            AssetCategoryInterface $assetCategoryRepo,
            BranchInterface        $branchRepo
        )
    {
        $this->repo               = $repo;
        $this->assetCategoryRepo  = $assetCategoryRepo;
        $this->branchRepo         = $branchRepo;
    }
    public function index()
    {
        return view('assets::index');
    }
 
    public function getAll(){
        
        $assets = $this->repo->get();
        return DataTables::of($assets)
        ->addIndexColumn()  
        ->editColumn('branch',function($asset){
            return @$asset->branch->name;
        }) 
        ->editColumn('asset_category',function($asset){
            return @$asset->category->title;
        })
        ->editColumn('name',function($asset){
            return $asset->name;
        })
        ->editColumn('invoice_no',function($asset){
            return $asset->invoice_no;
        })
        ->editColumn('supplier',function($asset){
            return $asset->supplier;
        })
        ->editColumn('quantity',function($asset){
            return $asset->quantity;
        })
        ->editColumn('warranty',function($asset){
            return $asset->warranty;
        })
        ->editColumn('amount',function($asset){
            return $asset->amount;
        })
        ->editColumn('description',function($asset){
            return $asset->description;
        })
        ->editColumn('created_by',function($asset){
            return @$asset->user->name;
        }) 
 
        ->editColumn('action',function($asset){
        $action = '';
        if(hasPermission('assets_update') || hasPermission('assets_delete')):
            $action .= ' <div class="dropdown">';
            $action .= '<a href="#" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
            $action .=  '<i class="fa fa-cogs"></i>';
            $action .=  '</a>';
            $action .=  '<div class="dropdown-menu todo-dropdown-menu" aria-labelledby="dropdownMenuButton">';
              
                if(hasPermission('assets_update')):
                    $action .=  '<a href="'. route('assets.edit',$asset->id) .'" class="dropdown-item"  data-title="'.__('assets').' '. __('edit') .'" ><i class="fas fa-pen" ></i>'.__('edit') .'</a>';
                endif;
                if(hasPermission('assets_delete')):
                    $action .=  '<form action="'. route('assets.delete',@$asset->id) .'" method="post" class="delete-form" id="delete" data-yes='. __('yes') .' data-cancel="'. __('cancel') .'" data-title="'. __('delete_assets') .'">';
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
        $branches           = $this->branchRepo->getAll(business_id());
        $assetCategories    = $this->assetCategoryRepo->getActive();
        return view('assets::create',compact('branches','assetCategories'));
    }
 
    public function store(StoreRequest $request)
    {
        if(env('DEMO')) {
            Toastr::error(__('store_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }
        if($this->repo->store($request)){
            Toastr::success(__('assets_added_successfully'),__('success')); 
            return redirect()->route('assets.index');
        }else{
            Toastr::error(__('error'),__('errors'));
            return redirect()->back();
        }
    }
 
    public function edit($id)
    {
        $asset  = $this->repo->getfind($id);
        $branches           = $this->branchRepo->getAll(business_id());
        $assetCategories    = $this->assetCategoryRepo->getActive();
        return view('assets::edit',compact('asset','branches','assetCategories'));
    }

    
    public function update(StoreRequest $request)
    {
        if(env('DEMO')) {
            Toastr::error(__('store_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }
        if($this->repo->update($request)){
            Toastr::success(__('assets_updated_successfully'),__('success'));
            return redirect()->route('assets.index');
        }else{
            Toastr::error(__('error'),__('errors'));
            return redirect()->back();
        }
    }

     
    public function destroy($id)
    {
        if(env('DEMO')) {
            Toastr::error(__('store_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }
        if($this->repo->delete($id)){
            Toastr::success(__('assets_deleted_successfully'),__('success'));
            return redirect()->back();
        }else{
            Toastr::error(__('error'),__('errors'));
            return redirect()->back();
        }
    }
 
 
}
