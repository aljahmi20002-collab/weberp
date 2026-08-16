<?php

namespace Modules\Plan\Entities;

use App\Enums\Status;
use App\Models\Backend\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Plan\Enums\IsDefault;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [];
    protected $casts    = ['modules'=>'array'];
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

    public function getMyDefaultAttribute(){
        if($this->is_default == IsDefault::YES):
            return true;
        else:
            return false;
        endif;
    }

    public function getIntvalNameAttribute(){
        $days = $this->days_count;
        $name = $days.' Days';
        if($days >= 365):
            $year = ($days / 365); 
            $name = (int)$year.' Years'; 
        elseif($days >= 30):
            $month = $days/30; 
            $name =  (int) $month.' Month';
        endif;
        return $name;
    }

    public function getHrmModulesAttribute (){
        return [
            'leave_type',
            'designation',
            'department',
            'leave_assign',
            'apply_leave',
            'available_leave',
            'leave_request',
            'weekend',
            'holiday',
            'duty_schedule',
            'attendance'
        ];
    }

}
