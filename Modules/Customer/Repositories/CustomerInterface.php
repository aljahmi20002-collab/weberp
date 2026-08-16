<?php
namespace Modules\Customer\Repositories;
interface CustomerInterface {
    public function get();
    public function getAllCustomers();
    public function getActiveCustomers($business_id);
    public function getFind($id);
    public function store($request);
    public function update($id,$request);
    public function delete($id);
    public function statusUpdate($id); 
    public function totalSalesPayments($customer_id);
}