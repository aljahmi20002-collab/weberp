<?php

namespace Modules\Reports\Repositories\AttendanceReport;

use App\Enums\Status;
use Carbon\Carbon;
use Dflydev\DotAccessData\Data;
use Modules\ApplyLeave\Entities\LeaveRequest;
use Modules\ApplyLeave\Enums\LeaveStatus;
use Modules\Attendance\Entities\Attendance;
use Modules\Attendance\Enums\AttendanceStatus;
use Modules\Holiday\Entities\Holiday;
use Modules\Reports\Repositories\AttendanceReport\AttendanceReportInterface;
use PhpParser\Node\Stmt\For_;

class AttendanceReportRepository implements AttendanceReportInterface
{
    public function dateFromTo($request)
    {
        $date = explode('To', $request->date);
        $data = [];
        if (is_array($date)) {
            $data['from']   = Carbon::parse(trim($date[0]))->startOfDay()->toDateTimeString();
            $data['to']     = Carbon::parse(trim($date[1]))->endOfDay()->toDateTimeString();
        }

        return $data;
    }



    public function getReport($request)
    {

        if ($request->from_user_view) :
            $date = explode('-', $request->date);
        else :
            $date = explode('To', $request->date);
        endif;
        $data = [];
        if (is_array($date)) {
            $data['from']   = Carbon::parse(trim($date[0]))->startOfDay()->toDateTimeString();
            $data['to']     = Carbon::parse(trim($date[1]))->endOfDay()->toDateTimeString();
        }

        $requestToDate = Carbon::parse($data['to'])->addSecond(1)->toDateTimeString();

        $totalData = [];
        $totalData['total_days']       = Carbon::parse($data['from'])->diffInDays($requestToDate);
        $totalData['total_holidays']   = 0;
        $totalData['total_leave_days'] = 0;
        $totalData['total_present']    = 0;
        $totalData['total_pending']    = 0;
        $totalData['total_absent']     = 0;
        $totalData['employee']     = $request->employee;

        //holiday check
        $holidays      = Holiday::where('status', Status::ACTIVE)->where(function ($query) use ($data) {
            $query->whereBetween('from', [$data['from'], $data['to']]);
            $query->orWhereBetween('from', [$data['from'], $data['to']]);
        })->get();
        $holiday_dates = [];
        foreach ($holidays as $holiday) {
            $days            = Carbon::parse($holiday->from)->diffInDays($holiday->to);
            for ($i = 0; $i <= $days; $i++) {
                $holiday_dates[] =  Carbon::parse($holiday->from)->addDays($i)->format('Y-m-d');
            }
        }
        //end holiday check

        //leave check

        $leave_requests  = LeaveRequest::with('user')->where('employee_id', $request->employee_id)->where('status', LeaveStatus::APPROVED)->where(function ($query) use ($data) {
            $query->whereBetween('leave_from', [$data['from'], $data['to']]);
            $query->orWhereBetween('leave_to', [$data['from'], $data['to']]);
        })->get();


        $leave_dates = [];
        foreach ($leave_requests as $key => $leave) {
            $leavedays            = Carbon::parse($leave->leave_from)->diffInDays($leave->leave_to);
            for ($i = 0; $i <= $leavedays; $i++) {
                $leave_dates[] =  Carbon::parse($leave->leave_from)->addDays($i)->format('Y-m-d');
            }
        }

        //end leave check
        $requestdays   = Carbon::parse($data['from'])->diffInDays($requestToDate);
        $requestDates  = [];
        for ($i = 0; $i < $requestdays; $i++) {
            $requestDates[] = Carbon::parse($data['from'])->addDays($i)->format('Y-m-d');
        }

        $totalData['request_dates'] = $requestDates;

        foreach ($requestDates as  $AttendanceDate) {
            $attendance = Attendance::where(['employee_id' => $request->employee_id])->whereDate('date', $AttendanceDate)->first();
            if (in_array($AttendanceDate, $holiday_dates)) : //holiday check
                $totalData['total_holidays']   += 1;
            elseif (in_array($AttendanceDate, $leave_dates)) : //leave check
                $totalData['total_leave_days'] += 1;
            elseif ($attendance) :
                if ($attendance->status == AttendanceStatus::PENDING) :
                    $totalData['total_pending']    += 1;
                else :
                    $totalData['total_present']    += 1;
                endif;
            else :
                $totalData['total_absent']         += 1;
            endif;
        }
        return $totalData;
    }

    public function getHolidays($request)
    {

        $reqdate = explode('To', $request->date);
        $data = [];
        if (is_array($reqdate)) {
            $data['from']   = Carbon::parse(trim($reqdate[0]))->startOfDay()->toDateTimeString();
            $data['to']     = Carbon::parse(trim($reqdate[1]))->endOfDay()->toDateTimeString();
        }

        //holiday check
        $holidays      = Holiday::where('status', Status::ACTIVE)->where(function ($query) use ($data) {
            $query->whereBetween('from', [$data['from'], $data['to']]);
            $query->orWhereBetween('to', [$data['from'], $data['to']]);
        })->get();

        return $holidays;
    }
}
