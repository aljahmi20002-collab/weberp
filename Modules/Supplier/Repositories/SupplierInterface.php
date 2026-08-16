<?php
namespace Modules\Supplier\Repositories;
interface SupplierInterface{
    public function get();
    public function getAll();
    public function getActive();
    public function getFind($id);
    public function store($request);
    public function update($id,$request);
    public function delete($id);
    public function statusUpdate($id); 
}