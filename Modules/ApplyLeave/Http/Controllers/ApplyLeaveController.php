<?php

namespace Modules\ApplyLeave\Http\Controllers;

use App\Repositories\User\UserInterface;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\ApplyLeave\Enums\LeaveStatus;
use Modules\ApplyLeave\Http\Requests\StoreRequest;
use Modules\ApplyLeave\Repositories\ApplyLeaveInterface;
use Modules\LeaveAssign\Repositories\LeaveAssignInterface;
use Yajra\DataTables\DataTables;

class ApplyLeaveController extends Controller
{
    protected $repo,$leaveAssignRepo,$userRepo;
    public function __construct(
            ApplyLeaveInterface $repo,
            LeaveAssignInterface $leaveAssignRepo,
            UserInterface   $userRepo
        )
    {
        $this->repo            = $repo;
        $this->leaveAssignRepo = $leaveAssignRepo;
        $this->userRepo        = $userRepo;
    }
    public function index(Request $request)
    { 

        return view('applyleave::index',compact('request'));
    }


    public function allAppliedLeave(){
        $applied_leaves           = $this->repo->getAllAppliedLeave();
        return DataTables::of($applied_leaves)
        ->addIndexColumn() 
        ->editColumn('applicant',function($applied_leave){
            return  @$applied_leave->user->name;
        })
        ->editColumn('leave_type',function($applied_leave){
            return  @$applied_leave->leaveType->name;
        })
        ->editColumn('leave_from',function($applied_leave){
            return @dateFormat2($applied_leave->leave_from);
        })
        ->editColumn('leave_to',function($applied_leave){
            return @dateFormat2($applied_leave->leave_to);
        })
        ->editColumn('file',function($applied_leave){
            return '<a href="'. @$applied_leave->file_path .'" download="">'. __('download') .'</a>';
        })
        ->editColumn('reason',function($applied_leave){
            return @$applied_leave->reason;
        })
        ->editColumn('status',function($applied_leave){
            return @$applied_leave->my_status;
        })
        ->editColumn('submited',function($applied_leave){
            return @dateFormat($applied_leave->created_at); 
        })
        ->editColumn('action',function($applied_leave){
            $action = '';
            if(hasPermission('apply_leave_update') || hasPermission('apply_leave_delete')):
                if($applied_leave->status == \Modules\ApplyLeave\Enums\LeaveStatus::PENDING ):
                    $action .= '<div class="dropdown">';
                    $action .= '<a href="#" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                    $action .= '<i class="fa fa-cogs"></i>';
                    $action .= '</a>';
                    $action .= '<div class="dropdown-menu todo-dropdown-menu" aria-labelledby="dropdownMenuButton">';  
                        if(hasPermission('apply_leave_delete')):
                            $action .= '<form action="'. route('hrm.apply.leave.delete',@$applied_leave->id) .'" method="post" class="delete-form" id="delete" data-yes='.__('yes') .' data-cancel="'. __('cancel') .'" data-title="'. __('delete_apply_leave') .'">';
                            $action .=method_field('delete');
                            $action .= csrf_field();
                                $action .= '<button type="submit" class="dropdown-item"  >';
                                $action .= '<i class="fas fa-trash-alt"></i>'. __('delete');
                                $action .= '</button>';
                                $action .= '</form>';
                        endif;
                        $action .= '</div>';
                        $action .= '</div> ';
                else:
                    $action .= '<i class="fa fa-ellipsis"></i>';
                endif; 
            else:
                return '...';
            endif;
            return $action;
        })
        ->rawColumns(['applicant', 'leave_type', 'leave_from', 'leave_to', 'file', 'reason', 'status', 'submited', 'action'])
        ->make(true);
    }


    public function PendingAppliedLeave(){
        $pending_applied_leaves   = $this->repo->pending();
        return DataTables::of($pending_applied_leaves)
        ->addIndexColumn() 
        ->editColumn('applicant',function($applied_leave){
        return  @$applied_leave->user->name;
        })
        ->editColumn('leave_type',function($applied_leave){
        return  @$applied_leave->leaveType->name;
        })
        ->editColumn('leave_from',function($applied_leave){
            return @dateFormat2($applied_leave->leave_from);
        })
        ->editColumn('leave_to',function($applied_leave){
            return @dateFormat2($applied_leave->leave_to);
        })
        ->editColumn('file',function($applied_leave){
            return '<a href="'. @$applied_leave->file_path .'" download="">'. __('download') .'</a>';
        })
        ->editColumn('reason',function($applied_leave){
            return @$applied_leave->reason;
        })
        ->editColumn('status',function($applied_leave){
            return @$applied_leave->my_status;
        })
        ->editColumn('submited',function($applied_leave){
            return @dateFormat($applied_leave->created_at); 
        })

        ->editColumn('action',function($applied_leave){
            $action = '';
            if(hasPermission('apply_leave_update') || hasPermission('apply_leave_delete')):
                if($applied_leave->status == \Modules\ApplyLeave\Enums\LeaveStatus::PENDING ):
                    $action .= '<div class="dropdown">';
                    $action .= '<a href="#" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                    $action .= '<i class="fa fa-cogs"></i>';
                    $action .= '</a>';
                    $action .= '<div class="dropdown-menu todo-dropdown-menu" aria-labelledby="dropdownMenuButton">';  
                        if(hasPermission('apply_leave_delete')):
                            $action .= '<form action="'. route('hrm.apply.leave.delete',@$applied_leave->id) .'" method="post" class="delete-form" id="delete" data-yes='.__('yes') .' data-cancel="'. __('cancel') .'" data-title="'. __('delete_apply_leave') .'">';
                            $action .=method_field('delete');
                            $action .= csrf_field();
                                $action .= '<button type="submit" class="dropdown-item"  >';
                                $action .= '<i class="fas fa-trash-alt"></i>'. __('delete');
                                $action .= '</button>';
                                $action .= '</form>';
                        endif;
                        $action .= '</div>';
                        $action .= '</div> ';
                else:
                    $action .= '<i class="fa fa-ellipsis"></i>';
                endif; 
            else:
                return '...';
            endif;
            return $action;
        })
    
        ->rawColumns(['applicant', 'leave_type', 'leave_from', 'leave_to', 'file', 'reason', 'status', 'submited', 'action'])
        ->make(true);
    }
    public function ApprovedAppliedLeave(){
        $approved_applied_leaves  = $this->repo->approved(); 
        return DataTables::of($approved_applied_leaves)
        ->addIndexColumn() 
        ->editColumn('applicant',function($applied_leave){
        return  @$applied_leave->user->name;
        })
        ->editColumn('leave_type',function($applied_leave){
        return  @$applied_leave->leaveType->name;
        })
        ->editColumn('leave_from',function($applied_leave){
            return @dateFormat2($applied_leave->leave_from);
        })
        ->editColumn('leave_to',function($applied_leave){
            return @dateFormat2($applied_leave->leave_to);
        })
        ->editColumn('file',function($applied_leave){
            return '<a href="'. @$applied_leave->file_path .'" download="">'. __('download') .'</a>';
        })
        ->editColumn('reason',function($applied_leave){
            return @$applied_leave->reason;
        })
        ->editColumn('status',function($applied_leave){
            return @$applied_leave->my_status;
        })
        ->editColumn('submited',function($applied_leave){
            return @dateFormat($applied_leave->created_at); 
        })
    
        ->rawColumns(['applicant', 'leave_type', 'leave_from', 'leave_to', 'file', 'reason', 'status', 'submited'])
        ->make(true);

    }
    public function rejectedAppliedLeave(){ 
        $rejected_applied_leaves  = $this->repo->rejected();
        return DataTables::of($rejected_applied_leaves)
        ->addIndexColumn() 
        ->editColumn('applicant',function($applied_leave){
        return  @$applied_leave->user->name;
        })
        ->editColumn('leave_type',function($applied_leave){
        return  @$applied_leave->leaveType->name;
        })
        ->editColumn('leave_from',function($applied_leave){
            return @dateFormat2($applied_leave->leave_from);
        })
        ->editColumn('leave_to',function($applied_leave){
            return @dateFormat2($applied_leave->leave_to);
        })
        ->editColumn('file',function($applied_leave){
            return '<a href="'. @$applied_leave->file_path .'" download="">'. __('download') .'</a>';
        })
        ->editColumn('reason',function($applied_leave){
            return @$applied_leave->reason;
        })
        ->editColumn('status',function($applied_leave){
            return @$applied_leave->my_status;
        })
        ->editColumn('submited',function($applied_leave){
            return @dateFormat($applied_leave->created_at); 
        })
    
        ->rawColumns(['applicant', 'leave_type', 'leave_from', 'leave_to', 'file', 'reason', 'status', 'submited'])
        ->make(true);
    }


    public function create()
    {
        $leave_assigns = $this->leaveAssignRepo->leaveAssign();
        $users         =  $this->userRepo->LeaveApplyUser();
        return view('applyleave::create',compact('leave_assigns','users'));
    }

    public function AssignedLeave(Request $request){
        if($request->ajax()):
            $assigned_leaves     = $this->leaveAssignRepo->AssignedLeave($request->employee_id);
            return view('applyleave::assigned_leave_options',compact('assigned_leaves'));
        endif;
        return '';
    }


    public function store(StoreRequest $request)
    {
        if(env('DEMO')) {
            Toastr::error(__('store_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }
        $leaveAssign   = $this->leaveAssignRepo->getFind($request->leave_assign_id);
        if($leaveAssign && $leaveAssign->days >= $this->repo->availableLeave($request)):
            if($this->repo->store($request)){
                Toastr::success(__('leave_applied_successfully'),__('success'));
                return redirect()->route('hrm.apply.leave.index');
            }else{
                Toastr::error(__('error'),__('errors'));
                return redirect()->back()->withInput($request->all());
            }

        elseif($leaveAssign == null):
            Toastr::error(__('error'),__('errors'));
            return redirect()->back()->withInput($request->all());
        elseif($this->repo->availableLeave($request)):
            Toastr::error(__('your_yearly_leave_facility_already_completed_or_requested_for_more_try_another_way'),__('errors'));
            return redirect()->back()->withInput($request->all());
        endif;

    }


    public function destroy($id)
    {
        if(env('DEMO')) {
            Toastr::error(__('delete_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }
        if($this->repo->delete($id)){
            Toastr::success(__('leave_apply_deleted_successfully'),__('success'));
            return redirect()->back();
        }else{
            Toastr::error(__('error'),__('errors'));
            return redirect()->back();
        }
    }


    public function AvailableLeave(){
        return view('applyleave::available_leave');
    }
    
    public function getAllAvailableLeave(){
        $leave_assigns = $this->leaveAssignRepo->getleaveAssignWithPaginate();
        return DataTables::of($leave_assigns)
        ->addIndexColumn()
        ->editColumn('leave_type',function($leave_assign){
        return @$leave_assign->leavetype->name;
        })
        ->editColumn('role',function($leave_assign){
        return @$leave_assign->role->name;
        })
        ->editColumn('total_days',function($leave_assign){
        return  @$leave_assign->days;
        })
        ->editColumn('remaining_days',function($leave_assign){
            return @MyLeave($leave_assign->id,Auth::user()->id,Auth::user()->role_id);
        })  
        ->rawColumns(['leave_type','role','total_days','remaining_days'])
        ->make(true);
    }

    public function leaveRequestList(){
        return view('applyleave::leave_request_list');
    }
    
    public function getAllLeaveRequest(){
        $leave_requests   = $this->repo->leave_request_list();

        return DataTables::of($leave_requests)
        ->addIndexColumn() 
        ->editColumn('applicant',function($leave_request){
            return  @$leave_request->user->name;
        })
        ->editColumn('leave_type',function($leave_request){
            return @$leave_request->leaveType->name;
        })
        ->editColumn('leave_from',function($leave_request){
            return @dateFormat2($leave_request->leave_from);
        })
        ->editColumn('leave_to',function($leave_request){
            return @dateFormat2($leave_request->leave_to);
        })
        ->editColumn('file',function($leave_request){
            return '<a href="'. @$leave_request->file_path .'" download="">'. __('download') .'</a>';
        })
        ->editColumn('reason',function($leave_request){
            return  @$leave_request->reason;
        })
        ->editColumn('status',function($leave_request){
            return @$leave_request->my_status;
        })
        ->editColumn('submited',function($leave_request){
            return @dateFormat($leave_request->created_at);
        })

        ->editColumn('action',function($leave_request){
            $action = '';
            $action .= '<div class="dropdown">';
            $action .='<a href="#" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
            $action .= '<i class="fa fa-cogs"></i>';
            $action .= '</a>';
            $action .= '<div class="dropdown-menu todo-dropdown-menu" aria-labelledby="dropdownMenuButton"> ';        
            $action .= '<button class="dropdown-item modalBtn"
                        data-title="'.__('application_for_leave') .'"
                        data-url="'. route('hrm.leave.request.details',@$leave_request->id) .'"
                        data-bs-toggle="modal"
                        data-modalsize="modal-xl"
                        data-bs-target="#dynamic-modal">';
                        $action .= '<i class="fa fa-eye "></i> '. __('details');
                        $action .= '</button>';
                if(hasPermission('leave_request_approval')):
                    if(
                        $leave_request->status != \Modules\ApplyLeave\Enums\LeaveStatus::APPROVED &&
                        $leave_request->status != \Modules\ApplyLeave\Enums\LeaveStatus::REJECTED
                    ):
                        $action .= '<a class="dropdown-item" href="'. route('hrm.leave.request.approval',[$leave_request->id,'status'=>\Modules\ApplyLeave\Enums\LeaveStatus::APPROVED]) .'"><i class="fa fa-check"></i>'. __('approve') .'</a>';
                        $action .= '<a class="dropdown-item " href="'. route('hrm.leave.request.approval',[$leave_request->id,'status'=>\Modules\ApplyLeave\Enums\LeaveStatus::REJECTED]) .'"><i class="fa fa-window-close"></i>'. __('reject') .'</a>';
                    endif;
                endif;
            $action .= '</div>';
            $action .= '</div>';
            
            return $action;

        })
    
        ->rawColumns(['applicant', 'leave_type', 'leave_from', 'leave_to', 'file', 'reason', 'status', 'submited','action'])
        ->make(true);

    
    }

    public function Requestdetails($id){
        $leave_request           = $this->repo->getFind($id);
        $assigned_leave_types    = $this->leaveAssignRepo->leaveAssign();
        return view('applyleave::leave_request_details_modal',compact('leave_request','assigned_leave_types'));
    }

    public function leaveRequestPrint($id){
        $leave_request           = $this->repo->getFind($id);
        $assigned_leave_types    = $this->leaveAssignRepo->leaveAssign();
        return view('applyleave::leave_request_print',compact('leave_request','assigned_leave_types'));
    }

    public function approval(Request $request,$id){

        if(env('DEMO')) {
            Toastr::error(__('update_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }
        if($this->repo->approval($id,$request)):
            if($request->status == LeaveStatus::APPROVED):
                Toastr::success(__('leave_request_approved_successfully'),__('success'));
                return redirect()->back();
            elseif($request->status == LeaveStatus::REJECTED):
                Toastr::success(__('leave_request_rejected_successfully'),__('success'));
                return redirect()->back();
            endif;
        else:
            Toastr::error(__('error'),__('errors'));
            return redirect()->back();
        endif;
    }

}
