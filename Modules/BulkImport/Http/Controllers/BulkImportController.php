<?php

namespace Modules\BulkImport\Http\Controllers;

use App\Imports\CategoryImport;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Branch\Repositories\BranchInterface;
use Modules\Brand\Repositories\BrandInterface;
use Modules\BulkImport\Repositories\BulkImportInterface;
use Modules\BulkImport\Traits\ReturnOnlyFilledArray;
use Modules\Category\Repositories\CategoryInterface;
use Modules\TaxRate\Repositories\TaxRateInterface;
use Modules\Unit\Repositories\UnitInterface;
use Modules\Variation\Repositories\VariationInterface;
use Modules\Warranties\Repositories\WarrantyInterface; 
class BulkImportController extends Controller
{
   use ReturnOnlyFilledArray;
   protected $bulkRepo,$unitRepo,$brandRepo,$categoryRepo,$branchRepo,$variationRepo,$taxrateRepo,$warrantyRepo;
   public function __construct(
         BulkImportInterface  $bulkRepo,
         UnitInterface        $unitRepo,
         BrandInterface       $brandRepo,
         CategoryInterface    $categoryRepo,
         BranchInterface      $branchRepo,
         VariationInterface   $variationRepo,
         TaxRateInterface     $taxrateRepo,
         WarrantyInterface     $warrantyRepo
      )
      {
         $this->bulkRepo      = $bulkRepo;  
         $this->unitRepo      = $unitRepo;
         $this->brandRepo     = $brandRepo;
         $this->categoryRepo  = $categoryRepo;
         $this->branchRepo    = $branchRepo;
         $this->variationRepo = $variationRepo;
         $this->taxrateRepo   = $taxrateRepo;
         $this->warrantyRepo  = $warrantyRepo;
      }  

   //==================================category bulk import================================
   public function categoryIndex (){

      return view('bulkimport::category-import.index');
   }
 
   public function CategoryBulkStore(Request $request){   
      $request['categories'] = $this->CategoryRequest($request);
      if($request->categories == null):
         return response()->json(['success'=>null],200);
      elseif($this->bulkRepo->CategoryStore($request->categories)):
         return response()->json(['success'=>true],200);
      else:
         return response()->json(['success'=>false],200);
      endif;  
   } 


   //==================================end category bulk import================================


   //==================================Brand bulk import================================
   public function brandIndex (){
      return view('bulkimport::brand-import.index');
   }

   public function BrandBulkStore(Request $request){  
      $request['brands'] = $this->BrandRequest($request);
      if($request->brands == null):
         return response()->json(['success'=>null],200);
      elseif($this->bulkRepo->BrandStore($request->brands)):
         return response()->json(['success'=>true],200);
      else:
         return response()->json(['success'=>false],200);
      endif; 

   } 

   //==================================End Brand bulk import================================


   //==================================Customer bulk import================================

   public function customerIndex (){
      return view('bulkimport::customer-import.index');
   }


   public function customerBulkStore(Request $request){ 
      $request['customers'] = $this->CustomerRequest($request);
      if($request->customers == null):
         return response()->json(['success'=>null],200);
      elseif($this->bulkRepo->CustomerStore($request->customers)):
         return response()->json(['success'=>true],200);
      else:
         return response()->json(['success'=>false],200);
      endif;  
   } 
 
   //==================================End customer bulk import================================


   //==================================supplier bulk import================================
   public function supplierIndex (){
      return view('bulkimport::supplier-import.index');
   }
   
   public function supplierBulkStore(Request $request){
      $request['suppliers'] = $this->SupplierRequest($request);  
      if($request->suppliers == null):
         return response()->json(['success'=>null],200);
      elseif($this->bulkRepo->SuppliersStore($request->suppliers)):
         return response()->json(['success'=>true],200);
      else:
         return response()->json(['success'=>false],200);
      endif; 
   }
   //==================================End supplier bulk import================================


   //==================================Product bulk import================================
   public function productIndex (){
      return view('bulkimport::product-import.index');
   }

   public function getUnitName(Request $request){
      if($request->ajax()):
          return response()->json($this->unitRepo->get()->pluck('name'));
      endif;
      return ''; 
  }
   public function getBrandName(Request $request){
      if($request->ajax()):
          return response()->json($this->brandRepo->get()->pluck('name'));
      endif;
      return ''; 
  }

  public function getWarrantyName(Request $request){
   if($request->ajax()):
       return response()->json($this->warrantyRepo->get()->pluck('name'));
   endif;
   return ''; 
}


   public function getCategoryName(Request $request){
      if($request->ajax()):
          return response()->json($this->categoryRepo->get()->pluck('name'));
      endif;
      return ''; 
  }
   public function getSubcategoryName(Request $request){
      if($request->ajax()):
          $category = $this->categoryRepo->categoryFind($request);
          return response()->json($this->categoryRepo->getSubcategory($category->id)->pluck('name'));
      endif;
      return ''; 
  }
   public function getBranchName(Request $request){
      if($request->ajax()):
          return response()->json($this->branchRepo->getAll(business_id())->pluck('name'));
      endif;
      return ''; 
   }
   public function getVariationName(Request $request){
      if($request->ajax()):
          return response()->json($this->variationRepo->get(business_id())->pluck('name'));
      endif;
      return ''; 
   }

   public function getVariationValues(Request $request){
      if($request->ajax()):
          return response()->json($this->variationRepo->getNameFind($request));
      endif;
      return ''; 
   }


   public function getTaxrateName(Request $request){
      if($request->ajax()):
          return response()->json($this->taxrateRepo->getTaxRate()->pluck('name'));
      endif;
      return ''; 
   }

   public function productBulkStore(Request $request){
     
      $products = $this->ProductRequest($request);  
      $request['products'] = $products;  
      if($request->products == null):
         return response()->json(['success'=>null],200);
      elseif($this->bulkRepo->ProductsStore($request->products)):
         return response()->json(['success'=>true],200);
      else:
         return response()->json(['success'=>false],200);
      endif; 
   }

   //================================== End Product bulk import================================
 

}
