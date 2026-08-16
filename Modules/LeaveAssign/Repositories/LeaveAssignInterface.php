<?php
namespace Modules\LeaveAssign\Repositories;
interface LeaveAssignInterface {
    public function get();
    public function getAllLeaveAssign();
    public function leaveAssign();
    public function AssignedLeave($employee_id);
    public function leaveAssignWithPaginate();
    public function getleaveAssignWithPaginate();
    public function getFind($id);
    public function store($request);
    public function update($id,$request);
    public function delete($id);
    public function statusUpdate($id);
    public function existsAssigned($request);
}
