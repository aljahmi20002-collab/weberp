<?php
namespace Modules\Weekend\Repositories;
interface WeekendInterface {
    public function get();
    public function getFind($id);
    public function update($id,$request);
    public function statusUpdate($id);
}
