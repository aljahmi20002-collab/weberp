<?php
namespace Modules\Pos\Repositories;
interface PosInterface{
    public function get();   
    public function getAllPos();   
    public function getFind($id);
    public function store($request);
    public function update($id,$request);
    public function delete($id);  
    public function VariationLocationFind($request);
    public function VariationLocationSearchFind($request);
    public function variationLocationItemFind($id);
    public function variationLocationItemFindGet($id);
    public function addPayment($request);
    public function getFindPayment($id);
    public function updatePayment($request);
    public function deletePayment($id);
    public function VariationLocationSkuFind($request);

    //business dashboard
    public function ThirtyDaysPosChart();
    //branch dashboard
    public function branchThirtyDaysPosChart();

    //get
    public function getInvoice();
    public function getCustomerPosSales($customer_id);
    public function getCustomerPosSalesPayments($customer_id);
}