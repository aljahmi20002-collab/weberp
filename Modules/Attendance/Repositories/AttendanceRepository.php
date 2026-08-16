<?php
namespace Modules\Attendance\Repositories;

use App\Enums\UserType;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Auth; 
use Modules\Attendance\Entities\Attendance;
use Modules\Attendance\Enums\AttendanceStatus;
use Modules\Attendance\Repositories\AttendanceInterface ;

class AttendanceRepository implements AttendanceInterface {
    protected $attendanceModel;
    public function __construct(Attendance $attendanceModel){
        $this->attendanceModel = $attendanceModel;
    }
    public function get(){
        return $this->attendanceModel::where(['employee_id'=>Auth::user()->id])->whereYear('date',Date('Y'))->orderByDesc('id')->paginate(15);
    }
   
    public function attendanceData(){
        $data=[];                
        $data['monthyear']        = Carbon::now()->format('F Y'); 
        $data['start_month']      = Carbon::now()->startOfMonth()->toDateTimeString(); 
        $data['end_month']        = Carbon::now()->endOfMonth()->addSecond(1)->toDateTimeString();
        $data['total_month_days'] = Carbon::parse($data['start_month'])->diffInDays($data['end_month']); 
        $data['full_month_dates'] = [];
        for($i=0;$i<$data['total_month_days'];++$i){
            $data['full_month_dates'][] =  Carbon::now()->startOfMonth()->addDay($i)->toDateString();
        }    
        return $data;
    }
 
    public function getFind($id){
        return $this->attendanceModel::find($id);
    }
    public function getFindDateWise($employee_id,$date){
        return  Attendance::where(['employee_id'=>$employee_id])->whereDate('date',$date)->first();
    }
    public function store($request){
        try {

             
            $employee_id  = $request->employee_id;
            $user         = User::find($employee_id);
            $date         = Carbon::parse($request->date)->format('Y-m-d');
            $attendance                = new $this->attendanceModel();
            $attendance->employee_id   = $employee_id;
            if(businessId($employee_id)):
                $attendance->business_id   = businessId($employee_id);
            endif;
            if($user->branch_id):
                $attendance->branch_id   = $user->branch_id;
            endif; 
            $attendance->date               = $date; 
            $attendance->check_in           = $request->check_in;
            $attendance->in_ip_address      = \Request::ip();
            if($request->check_out && !blank($request->check_out)):
                $attendance->out_ip_address      = \Request::ip();
                $attendance->check_out   = $request->check_out;      
                $attendance->status      = AttendanceStatus::CHECK_OUT;  
                //stay minutes 
                $stay_minutes = Carbon::parse($request->check_in)->diffInMinutes($request->check_out);
                $attendance->stay_time = $stay_minutes;
            endif;
            $attendance->save();
            return true;
        } catch (\Throwable $th) {  
            return false;
        }
    }
    public function update($id,$request){
        try {
     
            $attendance                  = $this->attendanceModel::find($id); 
            $attendance->check_in        = $request->check_in;
            if($request->check_out):
                $attendance->out_ip_address      = \Request::ip();
                $attendance->check_out   = $request->check_out;   
                $attendance->status      = AttendanceStatus::CHECK_OUT;     
                //stay minutes 
                $stay_minutes            = Carbon::parse($request->check_in)->diffInMinutes($request->check_out);
                $attendance->stay_time   = $stay_minutes; 
            endif;
            $attendance->save();

            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
    public function delete($id){
        return $this->attendanceModel::destroy($id);
    }
 
}
