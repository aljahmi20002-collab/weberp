<?php
namespace Modules\Attendance\Repositories;
interface AttendanceInterface {
    public function get();  
    public function getFind($id);
    public function getFindDateWise($employee_id,$date);
    public function store($request);
    public function update($id,$request);
    public function delete($id);  
    public function attendanceData();
}
