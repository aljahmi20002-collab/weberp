<?php

namespace Modules\Support\Http\Controllers;

use App\Enums\UserType;
use App\Repositories\User\UserInterface;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Department\Repositories\DepartmentInterface;
use Modules\Service\Repositories\ServiceInterface;
use Modules\Support\Enums\SupportStatus;
use Modules\Support\Http\Requests\AdminSupportRequest;
use Modules\Support\Repositories\AdminSupport\SupportInterface;
use Yajra\DataTables\DataTables;

class AdminSupportController extends Controller
{
    protected $repo,$departmentRepo,$serviceRepo,$userRepo;
    public function __construct(SupportInterface $repo,DepartmentInterface $departmentRepo,ServiceInterface $serviceRepo,UserInterface $userRepo)
    {
        $this->repo             = $repo;
        $this->departmentRepo   = $departmentRepo;
        $this->serviceRepo      = $serviceRepo;
        $this->userRepo         = $userRepo;
    }
    public function index()
    {
        $title = __('need_help');
        if(isSuperadmin()):
            $title = __('support');
        endif;
        return view('support::admin-support.index',compact('title'));
    }
 
    public function getTicket(){
        $tickets = $this->repo->get(); 
        return DataTables::of($tickets)
        ->addIndexColumn()
        ->editColumn('Details',function($ticket){
            $details = '';
            $details .= '<div class="row d-flex text-left">';
            $details .= '<div class="col-lg-12">';
            $details .= '<p class="mb-0"  >'.__('name').': '.@$ticket->user->name.'</p>';
            $details .= '<p class="mb-0"  >'.__('email').': '.@$ticket->user->email.'</p>'; 
            $details .= '<p  class="mb-0" >'.__('department').': '.@$ticket->department->name.'</p>'; 
            $details .= '<p  class="mb-0" >'.__('service').': '.@$ticket->service->name.'</p>';
            $details .= '</div>';
            $details .= '</div>';
            return $details;
        })
        ->editColumn('subject',function($ticket){
            return $ticket->subject;
        })
        ->editColumn('priority',function($ticket){
            return @__($ticket->priority);
        })
        ->editColumn('status',function($ticket){
             return  $ticket->my_status;
        })
        ->editColumn('action',function($ticket){

            $action  = '';
            
            $action .=  '<div class="dropdown">';
            $action .= '<a href="#" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
            $action .= ' <i class="fa fa-cogs"></i>';
            $action .= '</a>';
            $action .= '<div class="dropdown-menu todo-dropdown-menu" aria-labelledby="dropdownMenuButton">';
                
                if(hasPermission('supports_status_update')):
                    if($ticket->status ==  SupportStatus::RESOLVED || $ticket->status ==  SupportStatus::CLOSED):
                       
                    else: 
                        if(SupportStatus::PENDING == $ticket->status):
                            $action .= '<a class="dropdown-item"  href="'.route('ticket.status.update',[$ticket->id,'status'=> SupportStatus::PROCESSING]) .'"><i class="fa-solid fa-spinner"></i>'. __('processing') .' </a>';
                        elseif ( SupportStatus::PROCESSING == $ticket->status):
                            $action .= '<a class="dropdown-item"  href="'. route('ticket.status.update',[$ticket->id,'status'=> SupportStatus::RESOLVED]) .'"> <i class="fa fa-check"></i>'. __('resolved') .' </a>';
                            $action .= '<a class="dropdown-item"  href="'.route('ticket.status.update',[$ticket->id,'status'=> SupportStatus::CLOSED]) .'"> <i class="fa fa-close"></i>'. __('closed') .'</a>';
                        endif; 
                    endif;
                endif;
                $action .= '<a href="'. route('ticket.view',@$ticket->id) .'" class="dropdown-item"  ><i class="fas fa-eye"></i>'. __('view') .'</a>';  
                if(hasPermission('supports_update')):
                    $action .= '<a href="'. route('ticket.edit',@$ticket->id) .'" class="dropdown-item"  ><i class="fas fa-pen"></i>'. __('edit') .'</a>';
                endif;
                if(hasPermission('supports_delete')):
                    $action .=  '<form action="'. route('ticket.delete',@$ticket->id) .'" method="post" class="delete-form" id="delete" data-yes='. __('yes') .' '. 'data-cancel="'. __('cancel') .'" data-title="'. __(isSuperadmin() == true? 'delete_support':'delete_request').'">';
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
        if(isSuperadmin()):
            $title = __('support');
        endif; 
        $services      = $this->serviceRepo->getAdminSupportService();
        $departments   = $this->departmentRepo->getActiveAll();
        $users         = $this->userRepo->getAllBusinessUsers();
        return view('support::admin-support.create',compact('title','services','departments','users'));
    }

    public function store(AdminSupportRequest $request)
    {
        if($this->repo->store($request)): 
            Toastr::success(__(isSuperadmin() == true? 'support_added_successfully':'submit_a_request_added_successfully'),__('success'));
            return redirect()->route('ticket.index'); 
        else:
            Toastr::error(__('error'),__('errors'));
            return redirect()->back();
        endif;
    }
 
    public function edit($id)
    {
        $ticket = $this->repo->getFind($id);
        $services      = $this->serviceRepo->getAdminSupportService();
        $departments   = $this->departmentRepo->getActiveAll();
        $users         = $this->userRepo->getAllBusinessUsers();
        return view('support::admin-support.edit',compact('ticket','services','departments','users'));
    }

    public function update(AdminSupportRequest $request)
    {
        if($this->repo->update($request->id,$request)): 
            Toastr::success(__(isSuperadmin() == true? 'support_updated_successfully':'submit_a_request_updated_successfully'),__('success'));
            return redirect()->route('ticket.index'); 
        else:
            Toastr::error(__('error'),__('errors'));
            return redirect()->back();
        endif;
    }

    public function view($id){
        $ticket      = $this->repo->getFind($id);
        $chats = $this->repo->chats($id);   

        $title = __('need_help');
        if(isSuperadmin()):
            $title = __('support');
        endif; 

        return view('support::admin-support.view',compact('ticket','chats','title'));
    }

    public function reply(AdminSupportRequest $request){
        if($this->repo->reply($request)): 
            Toastr::success(__('message_sended_successfully'),__('success'));
            return redirect()->route('ticket.view',$request->support_id); 
        else:
            Toastr::error(__('error'),__('errors'));
            return redirect()->back();
        endif;
    }

    public function destroy($id)
    {
        if($this->repo->delete($id)): 
            Toastr::success(__(isSuperadmin() == true? 'support_deleted_successfully':'submit_a_request_deleted_successfully'),__('success'));
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
