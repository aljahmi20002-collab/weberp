<?php
namespace Modules\Purchase\Repositories\PurchaseReturn;
interface PurchaseReturnInterface {
    public function get();   
    public function getAllPurchaseReturn();   
    public function getFind($id);
    public function store($request);
    public function update($id,$request);
    public function delete($id); 
    public function VariationLocationFind($request);
    public function variationLocationItem($request);

    public function addPayment($request);
    public function getFindPayment($id);
    public function updatePayment($request);
    public function deletePayment($id);
    public function supplierWisePurchaseReturn($supplier_id);
    public function supplierWisePurchaseList($supplier_id);
    public function supplierWisePurchaseReturnPaymentList($supplier_id);
    public function getInvoice();
}