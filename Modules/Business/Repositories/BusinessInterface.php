<?php
namespace Modules\Business\Repositories;

interface BusinessInterface{
    public function get();
    public function getAllBusinesses();
    public function getAll();
    public function getFind($id);
    public function store($request);
    public function update($id,$request);
    public function delete($id);
    public function statusUpdate($id);
    public function CreateBusiness($request); 
}
