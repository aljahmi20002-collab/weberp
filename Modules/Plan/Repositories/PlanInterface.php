<?php
namespace Modules\Plan\Repositories;
interface PlanInterface {
    public function get();
    public function getActive();
    public function getFind($id);
    public function store($request);
    public function update($id,$request);
    public function delete($id);
    public function statusUpdate($id);
    public function addDefault($id);
    public function permissionModules();
}