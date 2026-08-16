<?php
namespace Modules\Purchase\Repositories;
interface PurchaseInterface {
    public function get();   
    public function getAllPurchase();   
    public function getFind($id);
    public function store($request);
    public function update($id,$request);
    public function delete($id);  
    public function statusUpdate($id,$status);
    public function VariationLocationSkuFind($request);
    public function VariationLocationFind($request);
    public function variationLocationItem($request);
    public function addPayment($request);
    public function getFindPayment($id);
    public function updatePayment($request);
    public function deletePayment($id); 
    public function supplierWisePurchase($supplier_id);
    public function supplierWisePurchaseList($supplier_id);
    public function  supplierWisePaymentList($supplier_id);
    public function getInvoice();
}