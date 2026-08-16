<?php
namespace Modules\AccountHead\Repositories;

interface AccountHeadInterface {
    public function get(); 
    public function getAccountHead(); 
    public function getIncomeActiveHead();
    public function getExpenseActiveHead();
    public function getFind($id);
    public function store($request);
    public function update($id,$request);
    public function delete($id); 
    public function statusUpdate($id);
}