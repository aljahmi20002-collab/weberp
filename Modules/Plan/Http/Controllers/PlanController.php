<?php

namespace Modules\Plan\Http\Controllers;

use App\Repositories\Role\RoleInterface;
use Brian2694\Toastr\Facades\Toastr;
use Database\Seeders\RoleSeeder;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Plan\Http\Requests\StoreRequest;
use Modules\Plan\Repositories\PlanInterface;
use Yajra\DataTables\DataTables;

class PlanController extends Controller
{
    protected $repo,$roleRepo;
    public function __construct(PlanInterface $repo,RoleInterface $roleRepo)
    {
        $this->repo     = $repo;
        $this->roleRepo = $roleRepo; 
    }
    public function  index (){ 
        return view('plan::index');
    }
    public function getAllPlans(){
        $plans   = $this->repo->get();
        return DataTables::of($plans)
        ->addIndexColumn() 
        ->editColumn('name',function($plan){
            return  @$plan->name;
        })
        ->editColumn('user_count',function($plan){
            return @$plan->user_count;
        }) 
        ->editColumn('days_count',function($plan){
            return  @$plan->days_count;
        }) 
        ->editColumn('price',function($plan){
            return  systemCurrency().' '.@$plan->price;
        }) 
        ->editColumn('description',function($plan){
            return '<a href="#" class="modalBtn" data-title="'. __('description') .' " data-toggle="modal" data-target="#dynamic-modal" data-url="'.route('plans.options',['description'=>$plan->description]) .'"><i class="fa fa-eye"></i></a> ';
        }) 
        ->editColumn('status',function($plan){
            return  @$plan->my_status;
        }) 
        ->editColumn('make_default',function($plan){
            $default ='';
            if($plan->my_default):
                $default .= '<span class="badge badge-pill badge-success">'. __('default').'</span>';
            else:
                $default .= '<form action="'.route('plans.default',@$plan->id) .'" method="post" class="delete-form" id="delete" data-yes='. __('yes') .' data-cancel="'. __('cancel') .'" data-title="'. __('add_default_message') .'">';
                $default .=method_field('PUT');
                $default .=csrf_field();
                $default .= '<button type="submit" class="badge  badge-pill badge-primary"><i class="fa fa-plus text-white"> </i> '. __('default') .' </button>';
                $default .='</form>'; 
            endif;
            return $default;
        }) 
        ->editColumn('position',function($plan){
            return @$plan->position; 
        }) 
        ->editColumn('modules',function($plan){
            $modules ='';
            if(!blank($plan->modules)): 
                $modules .= '<ul class="list-style-none text-left"> ';
                foreach ($plan->modules as $module){ 
                    $modules .= '<li><i class="fa fa-check text-success mr-10px"></i>'.__(@$module) .'</li>';
                }
                $modules .= '</ul>';
            endif;
            return $modules;
        }) 
        ->editColumn('action',function($plan){
            $action = '';
            if(hasPermission('plans_update') || hasPermission('plans_delete') || hasPermission('plans_status_update')): 
                $action.= '<div class="dropdown">';
                $action.= '<a href="#" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                $action.= '<i class="fa fa-cogs"></i>';
                $action.=  '</a>';
                $action.= '<div class="dropdown-menu todo-dropdown-menu" aria-labelledby="dropdownMenuButton">';
                        
                    if(hasPermission('plans_status_update')):
                        $action.=  '<a class="dropdown-item" href="'. route('plans.status.update',$plan->id) .'">';
                        $action.= $plan->status == \App\Enums\Status::ACTIVE? '<i class="fa fa-ban"></i>':'<i class="fa fa-check"></i>';
                        $action.=  @statusUpdate($plan->status);
                        $action.= '</a>';
                    endif;

                    if(hasPermission('plans_update')):
                        $action.=  '<a href="'. route('plans.edit',@$plan->id) .'" class="dropdown-item"  ><i class="fas fa-pen"></i>'. __('edit') .'</a>';
                    endif;
                    if(hasPermission('plans_delete')):
                        $action.=  '<form action="'. route('plans.delete',@$plan->id) .'" method="post" class="delete-form" id="delete" data-yes='. __('yes') .' data-cancel="'. __('cancel') .'" data-title="'. __('delete_plan') .'">';
                        $action.= method_field('delete');
                        $action.= csrf_field();
                        $action.= '<button type="submit" class="dropdown-item "   >';
                        $action.= '<i class="fas fa-trash-alt"></i>'. __('delete');
                        $action.=  '</button>';
                        $action.= '</form>';
                    endif; 
                    $action.= '</div>';
                    $action.= '</div> ';
            else:
                return ' <i class="fa fa-cogs"></i>';
            endif;
            return $action;
        }) 
        ->rawColumns(['name', 'user_count',  'days_count',  'price', 'description', 'status', 'make_default', 'position', 'modules', 'action'])
        ->make(true);

    }
    public function options(Request $request){
        $description  = $request->description; 
        return view('plan::options_modal',compact('description'));
    }
    public function  create (){
        $modules   =   $this->repo->permissionModules(); 
        return view('plan::create',compact('modules'));
    }
    public function  store (StoreRequest $request){
        if(env('DEMO')) {
            Toastr::error(__('store_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }

        if($this->repo->store($request)):
            Toastr::success(__('plan_added_successfully'), __('success'));
            return redirect()->route('plans.index');
        else:
            Toastr::error(__('error'), __('errors'));
            return redirect()->back();
        endif;
    }
    public function  edit ($id){
        $plan   = $this->repo->getFind($id);
        $roles  = $this->roleRepo->all();
        $modules   =   $this->repo->permissionModules(); 
        return view('plan::edit',compact('roles','plan','modules'));
    }
    public function update(StoreRequest $request){
        if(env('DEMO')) {
            Toastr::error(__('update_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }

        if($this->repo->update($request->id,$request)):
            Toastr::success(__('plan_updated_successfully'), __('success'));
            return redirect()->route('plans.index');
        else:
            Toastr::error(__('error'), __('errors'));
            return redirect()->back()->withInput($request->all());
        endif;
    }
    public function  destroy ($id){
        if(env('DEMO')) {
            Toastr::error(__('delete_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }

        if($this->repo->delete($id)):
            Toastr::success(__('plan_deleted_successfully'), __('success'));
            return redirect()->route('plans.index');
        else:
            Toastr::error(__('error'), __('errors'));
            return redirect()->back();
        endif;
    }
    public function  statusUpdate ($id){
        if(env('DEMO')) {
            Toastr::error(__('update_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }

        if($this->repo->statusUpdate($id)):
            Toastr::success(__('plan_updated_successfully'), __('success'));
            return redirect()->route('plans.index');
        else:
            Toastr::error(__('error'), __('errors'));
            return redirect()->back();
        endif;
    }

    public function addDefault($id){
        if($this->repo->addDefault($id)):
            Toastr::success(__('default_plan_added_successfully'), __('success'));
            return redirect()->back();
        else:
            Toastr::error(__('error'), __('errors'));
            return redirect()->back();
        endif;
    }
}
