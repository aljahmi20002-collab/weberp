<?php
namespace Modules\Account\Repositories;
interface AccountInterface {
    public function get(); 
    public function getAllAccounts(); 
    public function getBranchAccounts($branch_id);
    public function getBusinessActiveAccounts(); 
    public function getAdminActiveAccount(); 
    public function getFind($id);
    public function store($request);
    public function update($id,$request);
    public function delete($id);
    public function statusUpdate($id);
    public function getBankTransactions();
    public function defaultAccountStore($business_id);
    public function makeDefault($id);
}