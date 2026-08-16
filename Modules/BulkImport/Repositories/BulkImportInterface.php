<?php
namespace Modules\BulkImport\Repositories;
interface BulkImportInterface {
    public function CategoryStore($request);
    public function BrandStore($request);
    public function CustomerStore($request);
    public function SuppliersStore($request);
    public function ProductsStore($request);
}