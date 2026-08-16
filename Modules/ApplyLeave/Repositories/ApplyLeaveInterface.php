<?php
namespace Modules\ApplyLeave\Repositories;
interface ApplyLeaveInterface {
    public function get();
    public function getAllAppliedLeave();
    public function pending();
    public function approved();
    public function rejected();
    public function availableLeave($request);
    public function getFind($id);
    public function store($request);
    public function delete($id);
    public function leave_request_list();
    public function approval($id,$request);
}
