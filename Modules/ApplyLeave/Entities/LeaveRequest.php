<?php

namespace Modules\ApplyLeave\Entities;

use App\Models\Backend\Role;
use App\Models\Upload;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\File;
use Modules\ApplyLeave\Enums\LeaveStatus;
use Modules\LeaveAssign\Entities\LeaveAssign;
use Modules\LeaveType\Entities\LeaveType;

class LeaveRequest extends Model
{
    use HasFactory;

    protected $fillable = [];

    public function user(){
        return $this->belongsTo(User::class,'employee_id','id');
    }
    public function role(){
        return $this->belongsTo(Role::class,'role_id','id');
    }
    public function leaveAssign(){
        return $this->belongsTo(LeaveAssign::class,'leave_assign_id','id');
    }
    public function leaveType(){
        return $this->belongsTo(LeaveType::class,'type_id','id');
    }

    public function upload(){
        return $this->belongsTo(Upload::class,'file','id');
    }

    public function getFilePathAttribute()
    {
        if ($this->upload && !empty($this->upload->original['original']) && File::exists(public_path($this->upload->original['original']))) {
            return static_asset($this->upload->original['original']);
        }
        return static_asset('/') ;
    }

    public function getMyStatusAttribute(){
        if($this->status == LeaveStatus::PENDING){
            return '<span class="badge badge-pill badge-warning">'.__('pending').'</span>';
        }elseif($this->status == LeaveStatus::APPROVED){
            return '<span class="badge badge-pill badge-success">'.__('approved').'</span>';
        }elseif($this->status == LeaveStatus::REJECTED){
            return '<span class="badge badge-pill badge-danger">'.__('rejected').'</span>';
        }
    }

    public function getLeaveDaysAttribute(){
        $totalLeaveDays  = 0; 
        $start_time    =  Carbon::parse($this->leave_from)->startOfDay()->toDateTimeString();
        $end_time      =  Carbon::parse($this->leave_to)->endOfDay()->addMinute(1)->toDateTimeString();
        $totalLeaveDays+= Carbon::parse($start_time)->diff($end_time)->days;

        return $totalLeaveDays;
    }

}
