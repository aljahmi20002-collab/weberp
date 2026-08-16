<?php

namespace Modules\Attendance\Entities;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Attendance\Enums\AttendanceStatus;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [];


    public function user(){
        return $this->belongsTo(User::class,'employee_id','id');
    }

    public function getStaytimeAttribute(){
        if($this->status == AttendanceStatus::CHECK_OUT):
            $totalHours        = Carbon::parse($this->check_in)->diffInHours($this->check_out);
            $totalHoursMinutes = $totalHours * 60;
            $totalMinutes      = Carbon::parse($this->check_in)->diffInMinutes($this->check_out);
            $minutes           = ($totalMinutes - $totalHoursMinutes);
            return $totalHours.' H '.$minutes.' Minutes';
        endif;
        return __('not_check_out');
    }

}
