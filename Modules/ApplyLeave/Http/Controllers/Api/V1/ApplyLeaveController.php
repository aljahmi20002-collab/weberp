<?php

namespace Modules\ApplyLeave\Http\Controllers\Api\V1;

use App\Traits\ApiReturnFormatTrait;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\ApplyLeave\Enums\LeaveStatus;
use Modules\ApplyLeave\Http\Resources\v1\LeaveRequestResource;
use Modules\ApplyLeave\Repositories\ApplyLeaveInterface;

class ApplyLeaveController extends Controller
{
    use ApiReturnFormatTrait;
    protected $repo;
    public function __construct(ApplyLeaveInterface $repo)
    {
        $this->repo = $repo;
    }
    public function leaveRequest()
    {
        $leave_requests   = $this->repo->leave_request_list();
        return $this->responseWithSuccess('Leave request list',[
            'leave_request_list' => LeaveRequestResource::collection($leave_requests)
        ],200);
    }

    public function approval(Request $request,$id){ 
        if($this->repo->approval($id,$request)):
            if($request->status == LeaveStatus::APPROVED): 
                return $this->responseWithSuccess(__('leave_request_approved_successfully'),[],200);
            elseif($request->status == LeaveStatus::REJECTED): 
                return $this->responseWithError(__('leave_request_rejected_successfully'),[],400); 
            endif;
        else: 
            return $this->responseWithError(__('error'),[],400); 
        endif;

    }

}
