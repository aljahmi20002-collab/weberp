<?php
namespace Modules\BulkImport\Repositories;

use App\Repositories\Upload\UploadInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Branch\Entities\Branch;
use Modules\Brand\Entities\Brand;
use Modules\Brand\Repositories\BrandInterface;
use Modules\Category\Entities\Category;
use Modules\BulkImport\Repositories\BulkImportInterface;
use Modules\Category\Repositories\CategoryInterface;
use Modules\Customer\Entities\Customer;
use Modules\Customer\Http\Requests\StoreRequest;
use Modules\Product\Repositories\ProductInterface;
use Modules\Supplier\Entities\Supplier;
use Modules\TaxRate\Entities\TaxRate;
use Modules\TaxRate\Repositories\TaxRateInterface;
use Modules\Unit\Entities\Unit;
use Modules\Unit\Repositories\UnitInterface;
use Modules\Variation\Entities\Variation;
use Modules\Variation\Repositories\VariationInterface;
use Modules\Warranties\Entities\Warranty;
use Modules\Warranties\Repositories\WarrantyInterface;

class BulkImportRepository implements BulkImportInterface{
    protected $uploadRepo,$productRepo,$categoryRepo,$unitRepo,$brandRepo,$taxrateRepo,$variationRepo,$warrantyRepo;
    public function __construct(
        UploadInterface   $uploadRepo,
        CategoryInterface $categoryRepo,
        ProductInterface  $productRepo, 
        UnitInterface     $unitRepo, 
        BrandInterface    $brandRepo,
        TaxRateInterface  $taxrateRepo,
        VariationInterface $variationRepo,
        WarrantyInterface  $warrantyRepo
        )
    {
        $this->uploadRepo     = $uploadRepo;
        $this->categoryRepo   = $categoryRepo;
        $this->productRepo    = $productRepo;
        $this->unitRepo       = $unitRepo;
        $this->categoryRepo   = $categoryRepo;
        $this->brandRepo      = $brandRepo;
        $this->taxrateRepo    = $taxrateRepo;
        $this->variationRepo  = $variationRepo;
        $this->warrantyRepo   = $warrantyRepo;
    }
    //bulk category store
    public function CategoryStore($request){ 
        try {   
            foreach ($request as  $req_category) {   
                $req_category = (object) $req_category;
                $category               = new Category();
                $category->business_id  = business_id();
                $category->name         = $req_category->name;
                $category->description  = $req_category->description;
                $category->position     = $req_category->position; 
                if($req_category->image_link):  
                    $category->image_id  = $this->uploadRepo->linktoImageUpload('categories',$req_category->image_link);
                endif;
                if($req_category->category && !empty($req_category->category)):
                    $categoryFind = $this->categoryRepo->categoryFind($req_category);
                    $category->parent_id = $categoryFind->id?? null;
                endif;
                $category->save();
            } 
            return true;
        } catch (\Throwable $th) {   
            return false;
        }
    } 
    //end bulk category store
 
    //bulk Brand store
    public function BrandStore($request){ 
        try {   
            foreach ($request as  $req_brand) {   
                $req_brand           = (object) $req_brand;
                $brand               = new Brand();
                $brand->business_id  = business_id();
                $brand->name         = $req_brand->name;
                $brand->description  = $req_brand->description;
                $brand->position     = $req_brand->position;
                if($req_brand->logo_image_link): 
                    $brand->logo     = $this->uploadRepo->linktoImageUpload('brands',$req_brand->logo_image_link);
                endif;
                $brand->save();
            } 
            return true;
        } catch (\Throwable $th) {  
            return false;
        }
    } 
    //end bulk Brand store


    //bulk customer store
    public function CustomerStore($request){
        try {   
            foreach ($request as  $req_customer) {
                $req_customer           = (object) $req_customer; 
                $customer                 = new Customer();  
                $customer->business_id    = business_id();
                $customer->name           = $req_customer->name;
                $customer->phone          = $req_customer->phone;
                $customer->email          = $req_customer->email;
                if($req_customer->image_link):
                    $customer->image_id  = $this->uploadRepo->linktoImageUpload('customer',$req_customer->image_link);
                endif;
                $customer->address        = $req_customer->address;
                if($req_customer->opening_balance):
                $customer->opening_balance= $req_customer->opening_balance;
                $customer->balance        = $req_customer->opening_balance;
                endif; 
                $customer->save();
            } 
            return true;
        } catch (\Throwable $th) { 
            return false;
        }
    }
    //end bulk customer store


    //bulk suppliers store
    public function SuppliersStore($request){
        try {   
            foreach ($request as  $req_supplier) {
                $req_supplier             = (object) $req_supplier;  
                $supplier                 = new Supplier(); 
                $supplier->business_id    =  business_id();   
                $supplier->name           = $req_supplier->name;
                $supplier->company_name   = $req_supplier->company_name;
                $supplier->phone          =  $req_supplier->phone;
                $supplier->email          = $req_supplier->email;
                $supplier->address        = $req_supplier->address;
                if($req_supplier->opening_balance):
                    $supplier->opening_balance= $req_supplier->opening_balance;
                    $supplier->balance        = $req_supplier->opening_balance;
                endif; 
                $supplier->save();
            } 
            return true;
        } catch (\Throwable $th) { 
            return false;
        }
    }
    //end bulk suppliers store

    //bulk Products store
    public function ProductsStore($request){
        try {   
          
            foreach ($request as  $req_product) {
                $req_product             = (object) $req_product;  
                $branch  = Branch::where('business_id',business_id())->where('name',$req_product->branch_name)->first();
                if(business() && $branch):
                    $branches  = [$branch->id];
                else:
                    $branches  = [Auth::user()->branch_id];
                endif; 
                $unit        = Unit::where('business_id',business_id())->where('name',$req_product->unit_name)->first();
                $brand       = Brand::where('business_id',business_id())->where('name',$req_product->brand_name)->first();
                $warranty    = Warranty::where('business_id',business_id())->where('name',$req_product->warranty_name)->first();
                $category    = Category::where('business_id',business_id())->where('name',$req_product->category_name)->where('parent_id',null)->first();
               
                $subcategory = Category::where('business_id',business_id())->where('name',$req_product->subcategory_name)->where('parent_id',$category->id)->first();
                $variation   = Variation::where('business_id',business_id())->where('name',$req_product->variation_name)->first();
                if($variation && in_array($req_product->variation_values,$variation->value)):
                    $variationvalue = [$req_product->variation_values];
                else:
                    $variationvalue = [];
                endif; 
                $taxrate       = TaxRate::where('business_id',business_id())->where('name',$req_product->tax_name)->first();

                $productData   = new Request();  
                $productData['name']              = $req_product->name;
                $productData['image_link']        = $req_product->image_link;
                $productData['unit_id']           = $unit->id ?? null;
                $productData['brand_id']          = $brand->id?? null;
                $productData['warranty_id']       = $warranty->id?? null;
                $productData['category_id']       = $category->id?? null;
                $productData['subcategory_id']    = $subcategory->id?? null;
                $productData['branches']          = $branches;
                $productData['variation_id']      = $variation->id?? null;
                $productData['variation_values']  = $variationvalue;
                $productData['quantity']          = (int) $req_product->quantity?? 0;
                $productData['default_purchase_price'] = $req_product->purchase_price?? 0;
                $productData['profit_percent']    = $req_product->profit_percent?? 0;
                $productData['selling_price']     = $req_product->selling_price?? 0;
                $productData['tax_id'  ]          = $taxrate->id?? null;
                $productData['barcode_type']      = Auth::user()->business->barcode_type; 
                $productData['description']       = $req_product->description; 
                           
                $this->productRepo->store($productData);
            } 
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
    //end bulk Products store

}