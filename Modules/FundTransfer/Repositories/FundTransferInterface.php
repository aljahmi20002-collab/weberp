<?php
namespace Modules\FundTransfer\Repositories;
interface FundTransferInterface {
    public function get(); 
    public function getFind($id);
    public function store($request);
    public function update($id,$request);
    public function delete($id);
}