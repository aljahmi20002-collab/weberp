<?php

namespace Modules\Category\Http\Controllers;

use App\Enums\Status;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Business\Repositories\BusinessInterface;
use Modules\Category\Http\Requests\StoreRequest;
use Modules\Category\Repositories\CategoryInterface;
use Yajra\DataTables\DataTables;

class CategoryController extends Controller
{
    protected $repo,$businessRepo;
    public function __construct(CategoryInterface $repo,BusinessInterface $businessRepo)
    {
        $this->repo          = $repo;
        $this->businessRepo  = $businessRepo;
    }
    public function index(){
         
        return view('category::index');
    }

    public function getAll(){
        $categories   = $this->repo->getAll();
        return DataTables::of($categories)
        ->addIndexColumn() 
        ->editColumn('name',function($category){
                $name =  @$category->name;
                if($category->parent_id !=null):
                $name = '-- '.$name;
                endif;
                return $name;
        })
        ->editColumn('image',function($category){
            return '<img src="'. @$category->image .'" width="50px"/>';
        })
        ->editColumn('description',function($category){
            return  $category->description;
        })
        ->editColumn('status',function($category){
            return @$category->my_status;
        })
        ->editColumn('position',function($category){
            return $category->position;
        })
        ->addColumn('action',function($category){
            $action = '';
            if(hasPermission('category_update') || hasPermission('category_delete') || hasPermission('category_status_update')):
                $action .= ' <div class="dropdown">';
                $action .= '<a href="#" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                $action .= '<i class="fa fa-cogs"></i>';
                $action .= '</a>';
                $action .= '<div class="dropdown-menu todo-dropdown-menu" aria-labelledby="dropdownMenuButton">';
                        
                    if(hasPermission('category_status_update')):
                        $action .= '<a class="dropdown-item" href="'.route('category.status.update',$category->id) .'">';
                        $action .= $category->status ==  Status::ACTIVE? '<i class="fa fa-ban"></i>':'<i class="fa fa-check"></i>';
                        $action .= @statusUpdate($category->status);
                        $action .= '</a>';
                    endif;
                    if(hasPermission('category_update')):
                        $action .= '<a href="'.route('category.edit',@$category->id) .'" class="dropdown-item"  ><i class="fas fa-pen"></i>'. __('edit') .'</a>';
                    endif;
                    if(hasPermission('category_delete')):
                        $action .= '<form action="'. route('category.delete',@$category->id) .'" method="post" class="delete-form" id="delete" data-yes='. __('yes') .' data-cancel="'. __('cancel') .'" data-title="'.__('delete_category') .'">';
                        $action .= method_field('delete');
                        $action .= csrf_field();
                        $action .= '<button type="submit" class="dropdown-item"  >';
                        $action .= '<i class="fas fa-trash-alt"></i>'.__('delete') ;
                        $action .= '</button>';
                        $action .= '</form>';
                    endif;
                $action .= '</div>';
                $action .= '</div> ';
            else:
                return '...';
            endif; 
            
            return $action;

        })
        ->rawColumns(['name','image','description','status','position','action'])
        ->make(true);
    }
 
    public function create(Request $request)
    {
        $businesses       = $this->businessRepo->getAll();
        $parentCategories = $this->repo->getParentCategory($request);
        return view('category::create',compact('businesses','parentCategories'));
    }

    public function store(StoreRequest $request)
    { 
       
        if(env('DEMO')) {
            Toastr::error(__('store_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }

        if($this->repo->store($request)): 
            Toastr::success(__('category_added_successfully'), __('success'));
            return redirect()->route('category.index');
        else:
            Toastr::error(__('error'), __('errors'));
            return redirect()->back()->withInput();
        endif;
    }
 
    public function edit(Request $request,$id)
    {
        $category               = $this->repo->getFind($id);
        $request['business_id'] = $category->business_id;
        $parentCategories       = $this->repo->getParentCategory($request);
        $businesses = $this->businessRepo->getAll();
        return view('category::edit',compact('category','parentCategories','businesses'));
    }

   
    public function update(StoreRequest $request)
    {
        if(env('DEMO')) {
            Toastr::error(__('delete_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }

        if($this->repo->update($request->id,$request)):
            Toastr::success(__('category_updated_successfully'), __('success'));
            return redirect()->route('category.index');
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
            Toastr::success(__('category_deleted_successfully'), __('success'));
            return redirect()->route('category.index');
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
            Toastr::success(__('category_updated_successfully'), __('success'));
            return redirect()->route('category.index');
        else:
            Toastr::error(__('error'), __('errors'));
            return redirect()->back();
        endif;
    }
    
    public function parentCategories(Request $request){ 
        $parentCategories = $this->repo->getParentCategory($request);
        $categories = "<option selected disabled>". __('select').' '.__('parent_category') ."</option>";
        foreach ($parentCategories as $category) {
            $categories .= "<option value='".$category->id."'>".$category->name."</option>";
        }
        return $categories;
    }
}
