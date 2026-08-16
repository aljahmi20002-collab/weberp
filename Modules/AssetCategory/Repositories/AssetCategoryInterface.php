<?php
namespace Modules\AssetCategory\Repositories;
interface AssetCategoryInterface { 
    public function get();
    public function getActive();
    public function getfind($id);
    public function store($request);
    public function update($request);
    public function delete($id);
    public function statusUpdate($id);
}