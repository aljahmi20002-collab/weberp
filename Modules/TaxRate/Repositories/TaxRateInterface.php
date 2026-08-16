<?php
namespace Modules\TaxRate\Repositories;

interface TaxRateInterface {
    public function get();
    public function getTaxRate();
    public function getActive($business_id);
    public function getFind($id);
    public function store($request);
    public function update($id,$request);
    public function delete($id);
    public function statusUpdate($id); 
    public function getTaxRates($business_id);
}
