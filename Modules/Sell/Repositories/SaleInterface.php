<?php

namespace Modules\Sell\Repositories;

interface SaleInterface
{
    public function get();
    public function getAllSale();
    public function getFind($id);
    public function store($request);
    public function update($id, $request);
    public function delete($id);
    public function variationLocationItemFind($request);
    public function VariationLocationFind($request);
    public function variationLocationItem($request);
    public function addPayment($request);
    public function getFindPayment($id);
    public function updatePayment($request);
    public function deletePayment($id);
    //get
    public function getCustomerSales($customer_id);
    public function getCustomerSalesPayments($customer_id);
    public function getInvoice();
}
