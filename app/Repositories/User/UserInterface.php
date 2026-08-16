<?php
namespace App\Repositories\User;

Interface UserInterface {
    public function get();
    public function getAll();
    public function getAllBusinessUsers();
    public function LeaveApplyUser();
    public function attendanceUser();
    public function getUsers();
    public function store($request);
    public function edit($id);
    public function update($request);
    public function delete($id);
    public function permissionsUpdate($request);
    public function statusUpdate($id);
    public function BanUnban($id);
    public function getAttendanceUsers();
    public function getFilterAttendanceUsers($request);
    public function getReportsUsers();
    public function businessUsers($business_id);
    public function getBusinessUsers($business_id);

    public function checkUserCount();

}
