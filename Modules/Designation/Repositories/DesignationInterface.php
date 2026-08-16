<?php
namespace Modules\Designation\Repositories;
interface DesignationInterface
{
    public function get();
    public function getAllDesignations();
    public function getActiveAll();
    public function getFind($id);
    public function store($request);
    public function update($id,$request);
    public function delete($id);
    public function statusUpdate($id);
}
