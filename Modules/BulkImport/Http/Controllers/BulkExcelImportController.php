<?php

namespace Modules\BulkImport\Http\Controllers;

use App\Imports\BrandImport;
use App\Imports\CategoryImport;
use App\Imports\CustomerImport;
use App\Imports\ProductImport;
use App\Imports\SupplierImport;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;
use Modules\Branch\Repositories\BranchInterface;
use Modules\Brand\Repositories\BrandInterface;
use Modules\BulkImport\Http\Requests\BulkExcelImportRequest;
use Modules\BulkImport\Repositories\BulkImportInterface;
use Modules\Category\Repositories\CategoryInterface;
use Modules\TaxRate\Repositories\TaxRateInterface;
use Modules\Unit\Repositories\UnitInterface;
use Modules\Variation\Repositories\VariationInterface;
use Modules\Warranties\Repositories\WarrantyInterface;

class BulkExcelImportController extends Controller
{ 
    protected $bulkRepo,$categoryRepo,$unitRepo,$brandRepo,$taxRateRepo,$branchRepo,$warrantyRepo,$variationRepo;
    public function __construct(
            BulkImportInterface  $bulkRepo,
            CategoryInterface    $categoryRepo,
            UnitInterface        $unitRepo,
            BrandInterface       $brandRepo,
            TaxRateInterface     $taxRateRepo,
            BranchInterface      $branchRepo,
            WarrantyInterface    $warrantyRepo,
            VariationInterface   $variationRepo, 
        )
      {
        $this->bulkRepo      = $bulkRepo;   
       $this->categoryRepo   = $categoryRepo;
       $this->unitRepo       = $unitRepo;
       $this->brandRepo      = $brandRepo;
       $this->taxRateRepo    = $taxRateRepo;
       $this->branchRepo     = $branchRepo;
       $this->warrantyRepo   = $warrantyRepo;
       $this->variationRepo  = $variationRepo;
      }  
 
    //category excel import 
    public function categoryExcelBulkIndex(){
        return view('bulkimport::category-import.category_excel_import');
    }
    public function CategoryBulkExcelStore(BulkExcelImportRequest $request){
        try {  
            $import = Excel::import(new CategoryImport, $request->file('file')); 
            Toastr::success(__('category_import_successfully'),__('success'));
            return redirect()->back();
        } catch (ValidationException $th) {
            $failures = $th->failures(); 
            $errors = [];
            foreach ($failures as $failure) {
                $errors[$failure->attribute()] =  $failure->errors()[0]; 
            } 
            return redirect()->back()->withErrors($errors);

        }
    }

    //end category excel import
 
    //brand excel import 
    public function brandExcelBulkIndex(){
        return view('bulkimport::brand-import.brand_excel_import');
    }
    public function brandBulkExcelStore(BulkExcelImportRequest $request){
        try {  
            $import = Excel::import(new BrandImport, $request->file('file')); 
            Toastr::success(__('brand_import_successfully'),__('success'));
            return redirect()->back();
        } catch (ValidationException $th) {
            $failures = $th->failures(); 
            $errors = [];
            foreach ($failures as $failure) {
                $errors[$failure->attribute()] =  $failure->errors()[0]; 
            } 
            return redirect()->back()->withErrors($errors); 
        }
    } 
    //end brand excel import


    //customer excel import 
    public function customerExcelBulkIndex(){
        return view('bulkimport::customer-import.customer_excel_import');
    }
    public function customerBulkExcelStore(BulkExcelImportRequest $request){
        try {  
            $import = Excel::import(new CustomerImport, $request->file('file')); 
            Toastr::success(__('customer_import_successfully'),__('success'));
            return redirect()->back();
        } catch (ValidationException $th) {
            $failures = $th->failures(); 
            $errors = [];
            foreach ($failures as $failure) {
                $errors[$failure->attribute()] =  $failure->errors()[0]; 
            } 
            return redirect()->back()->withErrors($errors); 
        }
    } 
    //end customer excel import


    //supplier excel import 
    public function supplierExcelBulkIndex(){
        return view('bulkimport::supplier-import.supplier_excel_import');
    }
    public function supplierBulkExcelStore(BulkExcelImportRequest $request){
        try {  
            $import = Excel::import(new SupplierImport, $request->file('file')); 
            Toastr::success(__('supplier_import_successfully'),__('success'));
            return redirect()->back();
        } catch (ValidationException $th) {
            $failures = $th->failures(); 
            $errors = [];
            foreach ($failures as $failure) {
                $errors[$failure->attribute()] =  $failure->errors()[0]; 
            } 
            return redirect()->back()->withErrors($errors); 
        }
    } 
    //end supplier excel import

    //product excel import 
    public function productExcelBulkIndex(){
        $units          = $this->unitRepo->getActive();
        $brands         = $this->brandRepo->getActive();
        $warranties     = $this->warrantyRepo->getActive();
        $categories     = $this->categoryRepo->getActiveCategory(business_id());
        $branches       = $this->branchRepo->getAll(business_id());
        $variations     = $this->variationRepo->getVariation(business_id()); 
        $taxRates      = $this->taxRateRepo->getActive(business_id()); 

        return view('bulkimport::product-import.product_excel_import',compact('units','brands','warranties','categories','branches','variations','taxRates'));
    }

    public function getSubcategory(Request $request){
        if($request->ajax()):
            $subcategories  = $this->categoryRepo->getSubcategory($request->category_id); 
            $options  = '<option value="">'.__('select').' '. __('subcategory').'</option>';
            foreach ($subcategories as  $subcategory) {
                $options   .= '<option value="' . $subcategory->id.'" >'.$subcategory->id.' = '.$subcategory->name.'</option>';
            }
            return $options; 
        endif;
        return '';
    }

    public function getVariationValues(Request $request){
        if($request->ajax()):
            $variation = $this->variationRepo->getFind($request->id); 
            $options  = ''; 
            if($variation):
                foreach ($variation->value as  $value) {
                    if(!blank($value)):
                        $options   .= '<option value="'.$value.'" >'.$value.'</option>';
                    endif;
                }
            endif;
            return $options;
        endif;
        return '';
    }

    public function productBulkExcelStore(BulkExcelImportRequest $request){
        try {  
            $import = Excel::import(new ProductImport, $request->file('file')); 
            Toastr::success(__('product_import_successfully'),__('success'));
            return redirect()->back();
        } catch (ValidationException $th) {
            $failures = $th->failures(); 
            $errors = [];
            foreach ($failures as $failure) {
                $errors[$failure->attribute()] =  $failure->errors()[0]; 
            } 
            return redirect()->back()->withErrors($errors); 
        }
    } 
    //end product excel import

}
