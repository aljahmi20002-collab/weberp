<?php
namespace Modules\Brand\Repositories;
interface BrandInterface {
    public function get();  
    public function getActive();
    public function getBrands($business_id);
    public function getFind($id);
    public function store($request);
    public function update($id,$request);
    public function delete($id);
    public function statusUpdate($id); 
}