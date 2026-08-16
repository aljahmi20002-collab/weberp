<?php
namespace Modules\SaleProposal\Repositories;
interface SaleProposalInterface {
    public function get();    
    public function getFind($id);
    public function store($request);
    public function update($id,$request);
    public function delete($id);  
    public function variationLocationItemFind($request);
    public function VariationLocationFind($request);
    public function variationLocationItem($request);
    //payments
    public function addPayment($request);
    public function getFindPayment($id);
    public function updatePayment($request);
    public function deletePayment($id);
}