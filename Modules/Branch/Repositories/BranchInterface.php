<?php
namespace Modules\Branch\Repositories;
interface BranchInterface {
    public function get($business_id);
    public function getAllBranch($business_id);
    public function getBranch($business_id);
    public function getAll($business_id);
    public function getFind($id);
    public function store($request);
    public function update($id,$request);
    public function statusUpdate($id);
    public function delete($id);
    public function getBranches($business_id);

}
