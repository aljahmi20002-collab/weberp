<?php
namespace Modules\StockTransfer\Repositories;
interface StockTransferInterface {
    public function get();   
    public function getAllTransfer();   
    public function getFind($id);
    public function store($request);
    public function update($id,$request);
    public function delete($id);  
    public function VariationLocationFind($request);
    public function variationLocationItem($request);
    public function statusUpdate($id,$request);
    public function stockCheck($request);
}