<?php
namespace Modules\Product\Repositories;
interface ProductInterface {
    public function get();
    public function getAllProducts();
    public function getCategoryWiseProducts($category_id);
    public function getSubCategoryWiseProducts($category_id,$subcategory_id);
    public function branchWiseProducts($branch_id);
    public function getFind($id);
    public function store($request);
    public function update($id,$request);
    public function delete($id);
    public function duplicateStore($request);
    public function barcodePrintProductVariation($request);
    public function stockAlertProductVariationLocations();
    public function getSearchProducts($request);
    public function getProducts($request);
}
