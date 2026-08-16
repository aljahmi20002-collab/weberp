<?php
namespace Modules\ServiceSale\Repositories;
interface ServiceSaleInterface {
    public function get(); 
    public function getFind($id);
    public function store($request);
    public function update($id,$request);
    public function delete($id); 

    public function serviceItemFind($request);
    public function serviceItemsFind($request);
    public function serviceItem($request);
    //payment
    public function addPayment($request);
    public function getFindPayment($id);
    public function updatePayment($request);
    public function deletePayment($id);
    //reports
    public function getReport($request);
    //customer invoice
    public function getCustomerInvoice($customer_id);
    public function getCustomerInvoicePayments($customer_id);
}