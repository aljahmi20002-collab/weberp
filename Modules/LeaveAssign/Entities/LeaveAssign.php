<?php

namespace Modules\LeaveAssign\Entities;

use App\Enums\Status;
use App\Models\Backend\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\LeaveType\Entities\LeaveType;

class LeaveAssign extends Model
{
    use HasFactory;

    protected $fillable = [];

    public function leavetype(){
        return $this->belongsTo(LeaveType::class,'type_id','id');
    }
    public function role(){
        return $this->belongsTo(Role::class,'role_id','id');
    }
    public function getMyStatusAttribute(){
        if($this->status == Status::ACTIVE){
            return '<span class="badge badge-pill badge-success">'.__('active').'</span>';
        }elseif($this->status == Status::INACTIVE){
            return '<span class="badge badge-pill badge-danger">'.__('inactive').'</span>';
        }
    }

}
