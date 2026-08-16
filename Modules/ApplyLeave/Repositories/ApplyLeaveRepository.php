<?php
namespace Modules\ApplyLeave\Repositories;

use App\Enums\UserType;
use App\Models\User;
use App\Repositories\Upload\UploadInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Modules\ApplyLeave\Entities\LeaveRequest;
use Modules\ApplyLeave\Enums\LeaveStatus;
use Modules\ApplyLeave\Repositories\ApplyLeaveInterface;
use Modules\LeaveAssign\Repositories\LeaveAssignInterface;

class ApplyLeaveRepository implements ApplyLeaveInterface
{
    protected $upload,$leaveRequestModel,$leaveAssignRepo;
    public function __construct(LeaveRequest $leaveRequestModel,UploadInterface $upload,LeaveAssignInterface $leaveAssignRepo){
        $this->leaveRequestModel = $leaveRequestModel;
        $this->upload            = $upload;
        $this->leaveAssignRepo   = $leaveAssignRepo;
    }
    public function get(){
        if(isUser()):
            return $this->leaveRequestModel::where(['employee_id'=>Auth::user()->id])->whereYear('created_at',Date('Y'))->orderByDesc('id')->paginate(10);
        elseif(business()):
            return $this->leaveRequestModel::where(['business_id'=>business_id()])->whereYear('created_at',Date('Y'))->orderByDesc('id')->paginate(10);
        elseif(isSuperadmin()):
            return $this->leaveRequestModel::where(function($query){
                                                $query->whereHas('user',function($query){
                                                    $query->where('user_type',UserType::ADMIN);
                                                });
                                            })->whereYear('created_at',Date('Y'))->orderByDesc('id')->paginate(10);
        endif;
    }
    public function getAllAppliedLeave(){
        if(isUser()):
            return $this->leaveRequestModel::where(['employee_id'=>Auth::user()->id])->whereYear('created_at',Date('Y'))->orderByDesc('id')->get();
        elseif(business()):
            return $this->leaveRequestModel::where(['business_id'=>business_id()])->whereYear('created_at',Date('Y'))->orderByDesc('id')->get();
        elseif(isSuperadmin()):
            return $this->leaveRequestModel::where(function($query){
                                                $query->whereHas('user',function($query){
                                                    $query->where('user_type',UserType::ADMIN);
                                                });
                                            })->whereYear('created_at',Date('Y'))->orderByDesc('id')->get();
        endif;
    }

    public function pending(){
        return $this->leaveRequestModel::where(['status'=>LeaveStatus::PENDING])
                                        ->where(function($query){
                                            if(isUser()):
                                                $query->where('employee_id',Auth::user()->id);
                                            elseif(business()):
                                                $query->where('business_id',business_id());
                                            elseif(isSuperadmin()):
                                                $query->whereHas('user',function($query){
                                                    $query->where('user_type',UserType::ADMIN);
                                                });
                                            endif;
                                        })
                                        ->whereYear('created_at',Date('Y'))
                                        ->orderByDesc('id')
                                        ->get();
    }
    public function approved(){
        return $this->leaveRequestModel::where(['status'=>LeaveStatus::APPROVED])
                                        ->where(function($query){
                                            if(isUser()):
                                                $query->where('employee_id',Auth::user()->id);
                                            elseif(business()):
                                                $query->where('business_id',business_id());
                                            elseif(isSuperadmin()):
                                                $query->whereHas('user',function($query){
                                                    $query->where('user_type',UserType::ADMIN);
                                                });
                                            endif;
                                        })
                                        ->whereYear('created_at',Date('Y'))
                                        ->orderByDesc('id')
                                        ->get();
    }
    public function rejected(){
        return $this->leaveRequestModel::where(['status'=>LeaveStatus::REJECTED])
                                        ->where(function($query){
                                            if(isUser()):
                                                $query->where('employee_id',Auth::user()->id);
                                            elseif(business()):
                                                $query->where('business_id',business_id());
                                            elseif(isSuperadmin()):
                                                $query->whereHas('user',function($query){
                                                    $query->where('user_type',UserType::ADMIN);
                                                });
                                            endif;
                                        })
                                        ->whereYear('created_at',Date('Y'))
                                        ->orderByDesc('id')
                                        ->get();
    }
    public function availableLeave($request){
        try {
            if(isUser()):
                $employee_id    = Auth::user()->id;
            else:
                $user           = User::find($request->employee_id);
                $employee_id    = $user->id;
            endif;

            $leaveRequests = $this->leaveRequestModel::where([
                                                        'employee_id'    =>$employee_id,
                                                        'leave_assign_id'=>$request->leave_assign_id,
                                                        'status'         =>LeaveStatus::APPROVED
                                                    ])->whereYear('created_at',Date('Y'))->get();
            $approvedDays  = 0;
            $requestDays   = 0;

            foreach ($leaveRequests as  $leave) {
                 $start_time    = Carbon::parse($leave->leave_from)->startOfDay()->toDateTimeString();
                 $end_time      = Carbon::parse($leave->leave_to)->endOfDay()->addMinute(1)->toDateTimeString();
                 $approvedDays +=  Carbon::parse($start_time)->diff($end_time)->days;
            }

            $request_start_time    = Carbon::parse($request->leave_from)->startOfDay()->toDateTimeString();
            $request_end_time      = Carbon::parse($request->leave_to)->endOfDay()->addMinute(1)->toDateTimeString();
            $requestDays          += Carbon::parse($request_start_time)->diff($request_end_time)->days;
            return ($approvedDays + $requestDays);
        } catch (\Throwable $th) {
            return false;
        }
    }

    public function getFind($id){
        return $this->leaveRequestModel::find($id);
    }
    public function store($request){
        try {
            if(isUser()):
                $employee_id = Auth::user()->id;
                $role_id     = Auth::user()->role_id;
                $business_id = business_id();
            else:
                $user        = User::find($request->employee_id);
                $employee_id = $user->id;
                $role_id     = $user->role_id;
                $business_id = businessId($user->id);
            endif;

            $leave_assign                  = $this->leaveAssignRepo->getFind($request->leave_assign_id);
            $applyLeave                    = new $this->leaveRequestModel();
            $applyLeave->employee_id       = $employee_id;
            $applyLeave->business_id       = $business_id;
            $applyLeave->role_id           = $role_id;
            $applyLeave->leave_assign_id   = $request->leave_assign_id;
            $applyLeave->type_id           = $leave_assign->type_id;
            $applyLeave->manager           = $request->manager;
            $applyLeave->leave_from        = Carbon::parse($request->leave_from)->format('Y-m-d');
            $applyLeave->leave_to          = Carbon::parse($request->leave_to)->format('Y-m-d');
            $applyLeave->file              = $this->upload->upload('leave','',$request->file);
            $applyLeave->reason            = $request->reason;
            $applyLeave->save();
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    public function delete($id){
        try {
            $applyLeave = $this->leaveRequestModel::find($id);
            $this->upload->unlinkImage($applyLeave->file);
            $applyLeave->delete();
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
    public function leave_request_list(){
        if(isSuperadmin()):
            return $this->leaveRequestModel::where(function($query){
                                            $query->whereHas('user',function($query){
                                                $query->where('user_type',UserType::ADMIN);
                                            });
                                            })->whereYear('created_at',Date('Y'))
                                            ->orderByDesc('id')
                                            ->get();
        else:
            return $this->leaveRequestModel::whereNot('business_id',null)
                                            ->where('business_id',business_id())
                                            ->whereYear('created_at',Date('Y'))
                                            ->orderByDesc('id')
                                            ->get();

        endif;
    }
    public function approval($id,$request){
        try {
            $leave_request          = $this->leaveRequestModel::find($id);
            $leave_request->status  = $request->status;
            $leave_request->save();
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
}
