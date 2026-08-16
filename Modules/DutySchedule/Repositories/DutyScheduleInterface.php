<?php
namespace Modules\DutySchedule\Repositories;
interface DutyScheduleInterface {
    public function get();
    public function getAllDutySchedule();
    public function getActiveAll();
    public function getFind($id);
    public function store($request);
    public function update($id,$request);
    public function delete($id);
}
