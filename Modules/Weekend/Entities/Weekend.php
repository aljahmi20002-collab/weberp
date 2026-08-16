<?php

namespace Modules\Weekend\Entities;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Weekend\Enums\WeekendStatus;

class Weekend extends Model
{
    use HasFactory;

    protected $fillable = ['name','is_weekend','position','status'];

    public function getWeekendAttribute(){
        if($this->is_weekend == WeekendStatus::WEEKEND_NO){
            return '<span class="badge badge-pill badge-success">'.__('no').'</span>';
        }elseif($this->is_weekend == WeekendStatus::WEEKEND_YES){
            return '<span class="badge badge-pill badge-danger">'.__('yes').'</span>';
        }
    }
    public function getMyStatusAttribute(){
        if($this->status == Status::ACTIVE){
            return '<span class="badge badge-pill badge-success">'.__('active').'</span>';
        }elseif($this->status == Status::INACTIVE){
            return '<span class="badge badge-pill badge-danger">'.__('inactive').'</span>';
        }
    }
}
