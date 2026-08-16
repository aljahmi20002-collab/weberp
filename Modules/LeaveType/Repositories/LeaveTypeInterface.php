<?php
namespace Modules\LeaveType\Repositories;

interface LeaveTypeInterface
{
    public function get();
    public function getAllLeaveType();
    public function getActiveAll();
    public function getFind($id);
    public function store($request);
    public function update($id,$request);
    public function delete($id);
    public function statusUpdate($id);
}
