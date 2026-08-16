<?php
namespace Modules\Reports\Repositories\AttendanceReport;
interface AttendanceReportInterface {
    public function dateFromTo($request);
    public function getReport($request);
    public function getHolidays($request);
}