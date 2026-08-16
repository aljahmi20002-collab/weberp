<?php

namespace Modules\DutySchedule\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;
use App\Models\Backend\Role;
class DutySchedule extends Model
{
    use HasFactory;

    protected $fillable = [];

    protected static function newFactory()
    {
        return \Modules\DutySchedule\Database\factories\DutyScheduleFactory::new();
    }
    public function role(){
        return $this->belongsTo(Role::class,'role_id','id');
    }


}
