<?php

namespace Modules\Reports\Http\Resources\v1;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;


class AttendanceReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $request_dates = $this['request_dates'];
        $employee = $this['employee'];
        $attendance_list = array();
        foreach ($request_dates as $date) {
            $requestData['date'] = $date;
            if (ReportAttendanceFind($employee->id, $date) && ReportAttendanceFind($employee->id, $date)->check_in) {
                $requestData['check_in'] = Carbon::parse(ReportAttendanceFind($employee->id, $date)->check_in)->format('h:i A');
            } else {
                $requestData['check_in'] = '';
            }
            if (ReportAttendanceFind($employee->id, $date) && ReportAttendanceFind($employee->id, $date)->check_out) {
                $requestData['check_out'] = Carbon::parse(ReportAttendanceFind($employee->id, $date)->check_out)->format('h:i A');
            } else {
                $requestData['check_out'] = '';
            }
            $requestData['stay_time'] = @ReportAttendanceFind($employee->id, $date)->staytime;
            $requestData['status'] = @attendanceStatusText($date, $request);
            $attendance_list[] = $requestData;
        }
        return [
            "total_days"                 => $this['total_days'],
            "total_holidays"             => $this['total_holidays'],
            "total_leave_days"           => $this['total_leave_days'],
            "total_present"              => $this['total_present'],
            "total_pending"              => $this['total_pending'],
            "total_absent"               => $this['total_absent'],
            "attendance_list"            => $attendance_list,
            "employee"                   => $this['employee'],
        ];
    }
}
