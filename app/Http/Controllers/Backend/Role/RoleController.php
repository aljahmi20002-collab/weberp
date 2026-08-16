<?php

namespace App\Http\Controllers\Backend\Role;

use App\Http\Controllers\Controller;
use App\Http\Requests\Role\StoreRequest;
use App\Http\Requests\Role\UpdateRequest;
use App\Repositories\Role\RoleInterface;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Whoops\Run;
use Yajra\DataTables\DataTables;
class RoleController extends Controller
{
    protected $repo;
    public function __construct(RoleInterface $repo){
        $this->repo    = $repo;
    }
    //index
    public function index(){
        $roles       = $this->repo->get();
        return view('backend.role.index',compact('roles'));
    }

    public function allRoles(){
        $roles       = $this->repo->getAll();
        return DataTables::of($roles)
        ->addIndexColumn()
        ->editColumn('permissions',function($role){
            return '<span class="badge badge-pill badge-primary permission-count">'.count($role->permissions).'</span>';
        })
        ->editColumn('status',function($role){
            return $role->my_status;
        })
        ->addColumn('action',function($role){

            if(hasPermission('role_update') || hasPermission('role_delete')):
                $action = '';
                $action .= '<div class="dropdown">';
                $action .= '<a href="#" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                $action .= '<i class="fa fa-cogs"></i>';
                $action .= '</a>';
                $action .= '<div class="dropdown-menu todo-dropdown-menu" aria-labelledby="dropdownMenuButton">';          
                    if(hasPermission('role_update')):
                        $action .=  '<a href="'.route('role.edit',$role->id) .'" class="dropdown-item"   ><i class="fas fa-pen"></i>'. __('edit').'</a>';
                    endif;
                    if(hasPermission('role_delete')):
                        $action .=  '<form action="'.route('role.delete',$role->id) .'" method="post" class="delete-form" id="delete" data-yes='. __('yes').' data-cancel="'. __('cancel') .'" data-title="'. __('delete_role') .'">';
                        $action .= method_field('delete');
                        $action .= csrf_field();
                        $action .= '<button type="submit" class="dropdown-item"  >';
                        $action .= ' <i class="fas fa-trash-alt"> </i>'.__('delete');
                        $action .= '</button>';
                        $action .= '</form>';
                    endif;
                $action .= '</div>';
                $action .= '</div>';
                return $action;
            else:
                return '...';
            endif;


        })
        ->rawColumns(['permissions','status','action'])
        ->make(true);
    }


    //create
    public function create(){
        $permissions    = $this->repo->permissions();
        return view('backend.role.create',compact('permissions'));
    }
    //store
    public function store(StoreRequest $request){
        if(env('DEMO')) {
            Toastr::error(__('Store system is disable for the demo mode.'),__('errors'));
            return redirect()->back()->withInput();
        }
        if($this->repo->store($request)):
            Toastr::success(__('role_added'),__('success'));
            return redirect()->route('role.index');
        else:
            Toastr::error(__('error'),__('errors'));
            return redirect()->back()->withInput();
        endif;
    }

    //edit
    public function edit($id){
        $permissions     = $this->repo->permissions();
        $role            = $this->repo->edit($id);
        return view('backend.role.edit',compact('permissions','role'));
    }

    public function update(UpdateRequest $request){
        if(env('DEMO')) {
            Toastr::error(__('Update system is disable for the demo mode.'),__('errors'));
            return redirect()->back()->withInput();
        }
        if($this->repo->update($request)):
            Toastr::success(__('role_updated'),__('success'));
            return redirect()->route('role.index');
        else:
            Toastr::error(__('error'),__('errors'));
            return redirect()->back()->withInput();
        endif;
    }
    //delete
    public function delete($id){
        if(env('DEMO')) {
            Toastr::error(__('Delete system is disable for the demo mode.'),__('errors'));
            return redirect()->back();
        }
        if($this->repo->delete($id)){
            Toastr::success(__('role_deleted'),__('success'));
            return redirect()->route('role.index');
        }else{
            Toastr::error(__('error'),__('errors'));
            return redirect()->back();
        }
    }
}
