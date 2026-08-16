<?php
namespace Modules\Variation\Repositories;
interface VariationInterface{
    public function get();  
    public function getVariation($business_id);  
    public function getFind($id);
    public function getNameFind($request);
    public function store($request);
    public function update($id,$request);
    public function delete($id);
    public function statusUpdate($id); 
}