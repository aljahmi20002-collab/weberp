<?php
namespace Modules\Department\Repositories;
interface DepartmentInterface {
    public function get();
    public function getAllDepartment();
    public function getActiveAll();
    public function getFind($id);
    public function store($request);
    public function update($id,$request);
    public function delete($id);
    public function statusUpdate($id);
}
