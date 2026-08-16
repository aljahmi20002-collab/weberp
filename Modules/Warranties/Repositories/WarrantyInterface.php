<?php
namespace Modules\Warranties\Repositories;
interface WarrantyInterface {
    public function get();  
    public function getActive();  
    public function getFind($id);
    public function store($request);
    public function update($id,$request);
    public function delete($id);
    public function statusUpdate($id); 
}