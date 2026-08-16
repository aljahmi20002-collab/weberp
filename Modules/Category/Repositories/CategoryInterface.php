<?php
namespace Modules\Category\Repositories;
interface CategoryInterface{
    public function get(); 
    public function getAll();
    public function getActive();
    public function getParentCategory($request); 
    public function getFind($id);
    public function store($request);
    public function update($id,$request);
    public function delete($id);
    public function statusUpdate($id); 
    public function getActiveCategory($business_id);
    public function getSubcategory($category_id);
    public function subcategory($request);
    public function categoryFind($request);
}