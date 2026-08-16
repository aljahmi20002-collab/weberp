<?php

namespace Modules\Business\Http\Controllers;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Branch\Entities\Branch;
use Modules\Branch\Http\Requests\StoreRequest;
use Modules\Branch\Repositories\BranchInterface;
use Modules\Business\Repositories\BusinessInterface;
use Yajra\DataTables\DataTables;

class BusinessBranchController extends Controller
{

    protected $businessRepo,$branchRepo;
    public function __construct(BranchInterface $branchRepo,BusinessInterface $businessRepo)
    {
        $this->branchRepo = $branchRepo;
        $this->businessRepo = $businessRepo;
    }
    public function index($business_id)
    {

        $business       = $this->businessRepo->getFind($business_id); 
        return view('business::branch.index',compact('business','business_id'));
    }

     
    public function getAllBranch($business_id){
        $branches       = $this->branchRepo->getAllBranch($business_id);
        return DataTables::of($branches)
        ->addIndexColumn() 
        ->editColumn('name',function($branch){
            return @$branch->name; 
        })
        ->editColumn('email',function($branch){
            return @$branch->email;
        })
        ->editColumn('website',function($branch){
            return  @$branch->website;
        })
        ->editColumn('phone',function($branch){
            return  @$branch->phone;
        })
        ->editColumn('country',function($branch){
            return  @$branch->country->country;
        })
        ->editColumn('state',function($branch){
            return @$branch->state;
        })
        ->editColumn('city',function($branch){
            return @$branch->city;
        })
        ->editColumn('zip_code',function($branch){
            return @$branch->zip_code;
        })
        ->editColumn('status',function($branch){
            return  @$branch->my_status;
        })
        ->editColumn('action',function($branch)use($business_id){
            $action = '';
            if(hasPermission('business_branch_update') || hasPermission('business_branch_delete')):
        
                $action .= '<div class="dropdown">';
                $action .= '<a href="#" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                $action .= '<i class="fa fa-cogs"></i>';
                $action .=  '</a>';
                $action .= '<div class="dropdown-menu todo-dropdown-menu" aria-labelledby="dropdownMenuButton">';

                        if(hasPermission('business_branch_status_update')):
                            $action .= '<a class="dropdown-item" href="'.route('business.branch.status.update',$branch->id) .'">';
                            $action .= $branch->status == \App\Enums\Status::ACTIVE? '<i class="fa fa-ban"></i>':'<i class="fa fa-check"></i>';
                            $action .=  @statusUpdate($branch->status);
                            $action .=  '</a>';
                        endif;

                        if(hasPermission('business_branch_update')):
                            $action .=  '<a href="'.route('business.branch.edit',[$branch->id,'business_id'=>$business_id]) .'" class="dropdown-item"  ><i class="fas fa-pen"></i>'. __('edit') .'</a>';
                        endif;
                        if(hasPermission('business_branch_delete')):
                            $action .= '<form id="delete" action="'. route('business.branch.delete',@$branch->id) .'" method="post" class="delete-form"  data-yes='. __('yes') .' data-cancel="'.__('cancel') .'" data-title="'. __('delete_branch') .'">';
                            $action .= method_field('delete');
                            $action .= csrf_field();
                            $action .= '<button type="submit" class="dropdown-item  "   >';
                            $action .=  '<i class="fas fa-trash-alt"></i>'. __('delete');
                            $action .= '</button>';
                            $action .= '</form>';
                        endif;
                        $action .= '</div>';
                        $action .= '</div>';  
            else:
                return '<i class="fa fa-ellipsis"></i>';
            endif;

            return $action;
        })
        ->rawColumns(['name','email','website','phone','country','state','city','zip_code','status','action'])
        ->make(true);
    }
    public function create($business_id)
    {
        $business       = $this->businessRepo->getFind($business_id);
        return view('business::branch.create',compact('business_id','business'));
    }


    public function store(StoreRequest $request)
    {
        if(env('DEMO')){
            Toastr::error(__('store_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }
        if($this->branchRepo->store($request)){
            Toastr::success(__('branch_added_successfully'),__('success'));
            return redirect()->route('business.branch.index',$request->business_id);
        }else{
            Toastr::error(__('error'),__('errors'));
            return redirect()->back();
        }
    }

    public function edit(Request $request,$id)
    {
        $business_id    = $request->business_id;
        $branch         = $this->branchRepo->getFind($id);
        $business       = $this->businessRepo->getFind($business_id);
        return view('business::branch.edit',compact('branch','business','business_id'));
    }


    public function update(StoreRequest $request)
    { 
        if(env('DEMO')){
            Toastr::error(__('update_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }
        if($this->branchRepo->update($request->id,$request)){
            Toastr::success(__('branch_updated_successfully'),__('success'));
            return redirect()->route('business.branch.index',$request->business_id);
        }else{
            Toastr::error(__('error'),__('errors'));
            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        if(env('DEMO')){
            Toastr::error(__('delete_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }
        if($this->branchRepo->delete($id)){
            Toastr::success(__('branch_deleted_successfully'),__('success'));
            return redirect()->back();
        }else{
            Toastr::error(__('error'),__('errors'));
            return redirect()->back();
        }
    }

    public  function statusUpdate($id){
        if(env('DEMO')){
            Toastr::error(__('update_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }
        if($this->branchRepo->statusUpdate($id)){
            Toastr::success(__('branch_updated_successfully'),__('success'));
            return redirect()->back();
        }else{
            Toastr::error(__('error'),__('errors'));
            return redirect()->back();
        }
    }
}
