<?php
namespace Modules\Holiday\Repositories;
interface HolidayInterface {
    public function get();
    public function getAllHoliday();
    public function getActiveAll();
    public function getFind($id);
    public function store($request);
    public function update($id,$request);
    public function delete($id);
    public function statusUpdate($id);
}
