<?php

namespace Modules\Support\Http\Controllers;

use App\Repositories\User\UserInterface;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Department\Repositories\DepartmentInterface;
use Modules\Service\Repositories\ServiceInterface;
use Modules\Support\Enums\SupportStatus;
use Modules\Support\Http\Requests\StoreRequest;
use Modules\Support\Repositories\BusinessSupport\SupportInterface;
use Yajra\DataTables\DataTables;

class SupportController extends Controller
{

    protected $repo,$userRepo,$serviceRepo,$departmentRepo;
    public function __construct(
            SupportInterface $repo, 
            UserInterface $userRepo,
            ServiceInterface $serviceRepo,
            DepartmentInterface $departmentRepo
        )
    {
        $this->repo           = $repo;
        $this->userRepo       = $userRepo;
        $this->serviceRepo    = $serviceRepo;
        $this->departmentRepo = $departmentRepo;
    }
    public function index()
    {
        $title = __('need_help');
        if(business()):
            $title = __('support');
        endif;
        return view('support::business-support.index',compact('title'));
    }
 
    public function getSupport(){
        $supports = $this->repo->get(); 
        return DataTables::of($supports)
        ->addIndexColumn()
        ->editColumn('Details',function($support){
            $details = '';
            $details .= '<div class="row d-flex text-left">';
            $details .= '<div class="col-lg-12">';
            $details .= '<p class="mb-0"  >'.__('name').': '.@$support->user->name.'</p>';
            $details .= '<p class="mb-0"  >'.__('email').': '.@$support->user->email.'</p>'; 
            $details .= '<p  class="mb-0" >'.__('department').': '.@$support->department->name.'</p>'; 
            $details .= '<p  class="mb-0" >'.__('service').': '.@$support->service->name.'</p>';
            $details .= '</div>';
            $details .= '</div>';
            return $details;
        })
        ->editColumn('subject',function($support){
            return $support->subject;
        })
        ->editColumn('priority',function($support){
            return @__($support->priority);
        })
        ->editColumn('status',function($support){
            return  $support->my_status;
        })
        ->editColumn('action',function($support){
            $action  = '';
    
            $action .=  '<div class="dropdown">';
            $action .= '<a href="#" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
            $action .= ' <i class="fa fa-cogs"></i>';
            $action .= '</a>';
            $action .= '<div class="dropdown-menu todo-dropdown-menu" aria-labelledby="dropdownMenuButton">';
                
                
            if(hasPermission('support_status_update')):
                if($support->status ==  SupportStatus::RESOLVED || $support->status ==  SupportStatus::CLOSED):
                else: 
                    if(SupportStatus::PENDING == $support->status):
                        $action .= '<a class="dropdown-item"  href="'.route('support.status.update',[$support->id,'status'=> SupportStatus::PROCESSING]) .'"> <i class="fa-solid fa-spinner"></i>'. __('processing') .' </a>';
                    elseif ( SupportStatus::PROCESSING == $support->status):
                        $action .= '<a class="dropdown-item"  href="'. route('support.status.update',[$support->id,'status'=> SupportStatus::RESOLVED]) .'"><i class="fa fa-check"></i>'. __('resolved') .' </a>';
                        $action .= '<a class="dropdown-item"  href="'.route('support.status.update',[$support->id,'status'=> SupportStatus::CLOSED]) .'"> <i class="fa fa-close"></i>'. __('closed') .'</a>';
                    endif; 
                endif;
            endif;

            $action .= '<a href="'. route('support.view',@$support->id) .'" class="dropdown-item"  ><i class="fas fa-eye"></i>'. __('view') .'</a>'; 
            if(hasPermission('support_update')):
                $action .= '<a href="'. route('support.edit',@$support->id) .'" class="dropdown-item"  ><i class="fas fa-pen"></i>'. __('edit') .'</a>';
            endif;
            if(hasPermission('support_delete')):
                $action .=  '<form action="'. route('support.delete',@$support->id) .'" method="post" class="delete-form" id="delete" data-yes='. __('yes') .' '. 'data-cancel="'. __('cancel') .'" data-title="'. __(isSuperadmin() == true? 'delete_support':'delete_request').'">';
                $action .= method_field('delete');
                $action .= csrf_field();
                $action .= '<button type="submit" class="dropdown-item" >';
                $action .= '<i class="fas fa-trash-alt"></i>'. __('delete');
                $action .= '</button>';
                $action .= '</form>';
            endif;
            $action .= '</div>';
            $action .= '</div>';  
            return $action;

        })
        ->rawColumns(['Details','subject','priority','status','action'])
        ->make(true);
    }
    public function create()
    {
        $title = __('need_help');
        if(business()):
            $title = __('support');
        endif; 
        $services      = $this->serviceRepo->getAll();
        $departments   = $this->departmentRepo->getActiveAll();
        $users         = $this->userRepo->getBusinessUsers(business_id());
        return view('support::business-support.create',compact('title','services','departments','users'));
    }

    public function store(StoreRequest $request)
    {
        if($this->repo->store($request)): 
            Toastr::success(__(business() == true? 'support_added_successfully':'submit_a_request_added_successfully'),__('success'));
            return redirect()->route('support.index'); 
        else:
            Toastr::error(__('error'),__('errors'));
            return redirect()->back();
        endif;
    }
 
    public function edit($id)
    {
        $support        = $this->repo->getFind($id);
        $services      = $this->serviceRepo->getAdminSupportService();
        $departments   = $this->departmentRepo->getActiveAll();
        $users         = $this->userRepo->getBusinessUsers(business_id());
        return view('support::business-support.edit',compact('support','services','departments','users'));
    }

    public function update(StoreRequest $request)
    {
        if($this->repo->update($request->id,$request)): 
            Toastr::success(__(business() == true? 'support_updated_successfully':'submit_a_request_updated_successfully'),__('success'));
            return redirect()->route('support.index'); 
        else:
            Toastr::error(__('error'),__('errors'));
            return redirect()->back();
        endif;
    }

    public function view($id){
        $support      = $this->repo->getFind($id);
        $chats        = $this->repo->chats($id);   
        $title = __('need_help');
        if(business()):
            $title = __('support');
        endif; 
        return view('support::business-support.view',compact('support','chats','title'));
    }

    public function reply(StoreRequest $request){
        if($this->repo->reply($request)): 
            Toastr::success(__('message_sended_successfully'),__('success'));
            return redirect()->route('support.view',$request->support_id); 
        else:
            Toastr::error(__('error'),__('errors'));
            return redirect()->back();
        endif;
    }

    public function destroy($id)
    {
        if($this->repo->delete($id)): 
            Toastr::success(__(business() == true? 'support_deleted_successfully':'submit_a_request_deleted_successfully'),__('success'));
            return redirect()->back(); 
        else:
            Toastr::error(__('error'),__('errors'));
            return redirect()->back();
        endif;
    }

    public function statusUpdate(Request $request,$id){
        if($this->repo->statusUpdate($id,$request)):
            Toastr::success(__('status_updated_successfully'),__('success'));
            return redirect()->back();
        else:
            Toastr::error(__('error'),__('errors'));
            return redirect()->back();
        endif;
    }
    
}
