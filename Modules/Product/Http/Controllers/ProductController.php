<?php

namespace Modules\Product\Http\Controllers;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Modules\Branch\Repositories\BranchInterface;
use Modules\Brand\Repositories\BrandInterface;
use Modules\Business\Repositories\BusinessInterface;
use Modules\BusinessSettings\Repositories\BarcodeSettings\BarcodeSettingsInterface;
use Modules\Category\Repositories\CategoryInterface;
use Modules\Product\Entities\Product;
use Modules\Product\Http\Requests\LabelPrintRequest;
use Modules\Product\Http\Requests\StoreRequest;
use Modules\Product\Repositories\ProductInterface;
use Modules\Product\Traits\BusinessWiseDataFetch;
use Modules\TaxRate\Repositories\TaxRateInterface;
use Modules\Unit\Entities\Unit;
use Modules\Unit\Repositories\UnitInterface;
use Modules\Variation\Repositories\VariationInterface;
use Modules\Warranties\Repositories\WarrantyInterface;
use Yajra\DataTables\DataTables;

class ProductController extends Controller
{ 
    use BusinessWiseDataFetch;
    protected $repo,$businessRepo,$unitRepo,$brandRepo,$categoryRepo,$branchRepo,$taxRepo,$variationRepo,$warrantiesRepo,$barcodeSettingRepo;
    public function __construct(
        ProductInterface         $repo,
        BusinessInterface        $businessRepo,
        UnitInterface            $unitRepo,
        BrandInterface           $brandRepo,
        CategoryInterface        $categoryRepo,
        BranchInterface          $branchRepo,
        TaxRateInterface         $taxRepo,
        VariationInterface       $variationRepo,
        WarrantyInterface        $warrantiesRepo,
        BarcodeSettingsInterface $barcodeSettingRepo
        )
    {
        $this->repo               = $repo;
        $this->businessRepo       = $businessRepo;
        $this->unitRepo           = $unitRepo;
        $this->brandRepo          = $brandRepo;
        $this->categoryRepo       = $categoryRepo;
        $this->branchRepo         = $branchRepo;
        $this->taxRepo            = $taxRepo;
        $this->variationRepo      = $variationRepo;
        $this->warrantiesRepo     = $warrantiesRepo;
        $this->barcodeSettingRepo = $barcodeSettingRepo;
    }

    public function index()
    {
        $products   = $this->repo->get();  
        return view('product::index',compact('products'));
    }


    public function getAllProducts(){
        $products  = $this->repo->getAllProducts();
        return DataTables::of($products)
        ->addIndexColumn()  
        ->editColumn('image',function($product){
            return '<img class="img-thumbnail" src="'.@data_get($product->images,'image_one') .'" width="50px"/>';
        })
        ->editColumn('name',function($product){
            return @$product->name;
        })
        ->editColumn('sku',function($product){
            return  @$product->sku;
        })
        ->editColumn('branch',function($product){
            $branches   = '';
            foreach ($product->AllBranches as $branch){
                $branches  .= '<span class="badge badge-primary badge-pill">'.$branch->name .'</span>';
            }
            return $branches;
            
        })
        ->editColumn('purchase_price',function($product){
            return @$product->VariationMultiplePurchasePrice;
        })
        ->editColumn('selling_price',function($product){
            return @$product->VariationMultipleSellingPrice;
        })
        ->editColumn('available_quantity',function($product){
            return @$product->availableQuantity->sum('qty_available') .' '. $product->unit->short_name;
        })
        ->editColumn('category',function($product){
            return  @$product->category->name.'<br/> --'. @$product->subcategory->name;
        })
        ->editColumn('brand',function($product){
            return  @$product->brand->name;
        })
        ->editColumn('warranty',function($product){
            return  @$product->warranty->duration .' '. @$product->warranty->durationtypes;
        })
        ->addColumn('action',function($product){
                $action = '';
                $action .= '<div class="dropdown ">';
                $action .='<a href="#" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                $action .= '<i class="fa-solid fa-cogs"></i>';
                $action .= '</a>';
                $action .= '<div class="dropdown-menu todo-dropdown-menu" aria-labelledby="dropdownMenuButton">'; 
                $action .= '<a class="dropdown-item modalBtn" href="#" data-url="'.route('product.view',$product->id).'" data-bs-target="#dynamic-modal" data-modalsize="modal-xl"  data-bs-toggle="modal" data-title="'.@$product->name.'"> <i class="fa fa-eye"></i>'. __('view') .'</a> ';
                $action .= '<a class="dropdown-item" href="'.route('product.labels',$product->id) .'"><i class="fa fa-barcode"></i>'. __('labels') .'</a> ';
                if(hasPermission('product_update')):
                    $action .= '<a class="dropdown-item" href="'.route('product.duplicate.create',$product->id) .'"><i class="fa fa-reply"></i>'. __('duplicate') .'</a>';
                endif;
                if(hasPermission('product_update')):
                    $action .= '<a href="'. route('product.edit',$product->id).'" class="dropdown-item"  ><i class="fa fa-pen"></i>'. __('edit').'</a>';
                endif;
                if(hasPermission('product_delete')):
                    $action .= '<form action="'.route('product.delete',@$product->id).'" method="post" class="delete-form" id="delete"  data-yes='. __('yes') .' data-cancel="'. __('cancel') .'" data-title="'.__('delete_product') .'">';
                    $action .= method_field('delete');
                    $action .= csrf_field();
                    $action .= '<button type="submit" class="dropdown-item"  >';
                    $action .= '<i class="fa fa-trash"></i>'. __('delete');
                    $action .= '</button>';
                    $action .= '</form>';
                endif;
                $action .= '</div>';
                $action .= '</div>';

                return $action;
        }) 
        ->rawColumns(['image','name','sku','branch','purchase_price','selling_price','available_quantity','category', 'brand','warranty','action'])
        ->make(true);
    }
 
    public function create()
    { 
        $brands     = $this->brandRepo->getBrands(business_id()); 
        $units      = $this->unitRepo->getUnits(business_id());
        $categories = $this->categoryRepo->getActiveCategory(business_id());
        $taxRates   = $this->taxRepo->getActive(business_id());
        $branches   = $this->branchRepo->getBranches(business_id());
        $variations = $this->variationRepo->getVariation(business_id()); 
        $business   = $this->businessRepo->getFind(business_id());
        $warranties = $this->warrantiesRepo->getActive();

        return view('product::create',compact( 'units','categories','taxRates','brands','branches','variations','business','warranties'));
    }
 
    public function store(StoreRequest $request)
    {
        if(env('DEMO')) {
            Toastr::error(__('store_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }

        if($this->repo->store($request)):
            Toastr::success(__('product_added_successfully'), __('success'));
            return redirect()->route('product.index');
        else:
            Toastr::error(__('error'), __('errors'));
            return redirect()->back();
        endif;
    }
 
    public function view($id)
    {
        $product  = $this->repo->getFind($id);
        return view('product::view_modal',compact('product'));
    }

   
    public function edit($id)
    {
        $product = $this->repo->getFind($id);

        $brands     = $this->brandRepo->getBrands(business_id()); 
        $units      = $this->unitRepo->getUnits(business_id());
        $categories = $this->categoryRepo->getActiveCategory(business_id());
        $subcategories= $this->categoryRepo->getSubcategory($product->category_id);
        $taxRates   = $this->taxRepo->getActive(business_id());
        $branches   = $this->branchRepo->getBranches(business_id());
        $variations =  $this->variationRepo->getVariation(business_id()); 
        $singleVariation  = $this->variationRepo->getFind($product->variation_id);
        $business   = $this->businessRepo->getFind(business_id());
        $warranties = $this->warrantiesRepo->getActive();
 
        return view('product::edit',compact('product', 'units','categories','subcategories','taxRates','brands','branches','variations','singleVariation','business','warranties'));
    }
 
    public function update(StoreRequest $request)
    {
        if(env('DEMO')) {
            Toastr::error(__('update_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }

        if($this->repo->update($request->id,$request)):
            Toastr::success(__('product_updated_successfully'), __('success'));
            return redirect()->route('product.index');
        else:
            Toastr::error(__('error'), __('errors'));
            return redirect()->back();
        endif;
    }

  
    public function destroy($id)
    {
        if(env('DEMO')) {
            Toastr::error(__('delete_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }

        if($this->repo->delete($id)):
            Toastr::succes(__('product_delete_successfully'), __('success'));
            return redirect()->back();
        else:
            Toastr::error(__('error'), __('errors'));
            return redirect()->back();
        endif;
    }

    public function category(Request $request){
        if($request->ajax()):
            $categories   = $this->categoryRepo->getActiveCategory($request->business_id);
            return $this->categoryOptions($categories);
        else:
            return '<option disabled selected>'.__('select').' '. __('subcategory').'</option>';
        endif;
    }
    public function subcategory(Request $request){
        if($request->ajax()): 
            $subcategories   = $this->categoryRepo->subcategory($request);
            return $this->subcategoryOptions($subcategories);
        else:
            return '<option disabled selected>'.__('select').' '. __('subcategory').'</option>';
        endif;
    }

    public function branches(Request $request){
        if($request->ajax()):
            
            $branches = $this->branchRepo->getBranches($request->business_id);
            return $this->brandsOptions($branches);
        else:
            return '';
        endif;
    }

    public function applicableTax(Request $request){
        if($request->ajax()):
            $taxRates = $this->taxRepo->getTaxRates($request->business_id);
            return $this->applicableTaxOptions($taxRates);
        else:
            return '';
        endif;
    }
 
    public function brands(Request $request){
        if($request->ajax()):
            $brands   = $this->brandRepo->getBrands($request->business_id);
            return $this->brandsOptions($brands);
        else:
            return '';
        endif;
    }
    public function units(Request $request){
        if($request->ajax()):
            $units   = $this->unitRepo->getUnits($request->business_id);
            return $this->unitOptions($units);
        else:
            return '';
        endif;
    }

    public function VariationValues(Request $request){
        if($request->ajax()): 
            $variation   = $this->variationRepo->getFind($request->id);
            return $this->variationValueOptions($variation);
        else:
            return '';
        endif;
    }   
  

    public function duplicateCreate($id){
        $product = $this->repo->getFind($id);

        $brands     = $this->brandRepo->getBrands(business_id()); 
        $units      = $this->unitRepo->getUnits(business_id());
        $categories = $this->categoryRepo->getActiveCategory(business_id());
        $subcategories= $this->categoryRepo->getSubcategory($product->category_id);
        $taxRates   = $this->taxRepo->getActive(business_id());
        $branches   = $this->branchRepo->getBranches(business_id());
        $variations =  $this->variationRepo->getVariation(business_id()); 
        $singleVariation  = $this->variationRepo->getFind($product->variation_id);
        $business   = $this->businessRepo->getFind(business_id());
        $warranties = $this->warrantiesRepo->get();
 
        return view('product::duplicate',compact('product', 'units','categories','subcategories','taxRates','brands','branches','variations','singleVariation','business','warranties'));
    }
    
    public function duplicateStore(StoreRequest $request){
        if(env('DEMO')) {
            Toastr::error(__('store_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }

        if($this->repo->duplicateStore($request)):
            Toastr::success(__('product_duplicated_successfully'), __('success'));
            return redirect()->route('product.index');
        else:
            Toastr::error(__('error'), __('errors'));
            return redirect()->back();
        endif;
    }

    public function labels(Request $request,$id){
        $product            = $this->repo->getFind($id); 
        $barcode_settings   = $this->barcodeSettingRepo->getAll(); 
       return view('product::labels',compact('product','barcode_settings'));
    }
    public function labelPrint(LabelPrintRequest $request){ 
        $product           = $this->repo->getFind($request->id);
        $productVariations =  $this->repo->barcodePrintProductVariation($request);   
        $barcode_setting   = $this->barcodeSettingRepo->getFind($request->label_settings); 
        return view('product::label_print',compact('productVariations','product','barcode_setting','request'));
    }

    public function stockAlert(Request $request){  
        return view('product::stock_alert');
    }
    public function stockAlertSearchProducts(Request $request){ 
        $stockAlertProductVariationLocations = $this->repo->stockAlertProductVariationLocations($request); 
        return view('product::stock_alert_list',compact('stockAlertProductVariationLocations'));
    } 
    public function productSearch(Request $request){ 
        if(!blank($request->search)):
            $products = $this->repo->getSearchProducts($request);
        else:
            $products   = $this->repo->get();  
        endif;
        return view('product::product_list',compact('products'));
    }


    public function stockAlertGetProducts(){
        $stockAlertProductVariationLocations = $this->repo->stockAlertProductVariationLocations(); 
        
        return DataTables::of($stockAlertProductVariationLocations)
        ->addIndexColumn()
        ->editColumn('sku',function($variationLocation){
            return @$variationLocation->ProductVariation->sub_sku;
        })
        ->editColumn('product',function($variationLocation){

            return '<a class="text-primary modalBtn" href="#" data-url="'.route('product.view',@$variationLocation->product->id).'" data-bs-target="#dynamic-modal" data-modalsize="modal-xl"  data-bs-toggle="modal" data-title="'.@$variationLocation->product->name.'">'.@$variationLocation->product->name.'</a> ';
        })
        ->editColumn('branch',function($variationLocation){
            return @$variationLocation->branch->name;
        })
        ->editColumn('variation',function($variationLocation){
            return @$variationLocation->ProductVariation->name;
        })
        
        ->editColumn('category',function($variationLocation){
            return  @$variationLocation->product->category->name .'<br/> --'. @$variationLocation->product->subcategory->name;
        })
        
        ->editColumn('purchase_price',function($variationLocation){
            return  businessCurrency($variationLocation->product->business_id)  .' '.@$variationLocation->ProductVariation->default_purchase_price;
        })
        
        ->editColumn('selling_price',function($variationLocation){
            return    businessCurrency($variationLocation->product->business_id)  .' '.@$variationLocation->ProductVariation->sell_price_inc_tax;
        })
        
        ->editColumn('current_stock_selling_price',function($variationLocation){
            return  businessCurrency($variationLocation->product->business_id) .' '.@number_format($variationLocation->ProductVariation->sell_price_inc_tax * $variationLocation->qty_available,2);
        })
        ->editColumn('profit_percent',function($variationLocation){
            return  @$variationLocation->ProductVariation->profit_percent;
        })
        ->editColumn('available_quantity',function($variationLocation){
            return  @$variationLocation->qty_available.' '. $variationLocation->product->unit->short_name;
        })  
        ->rawColumns([
            'sku','product','branch','variation','category','purchase_price','selling_price','current_stock_selling_price','profit_percent','available_quantity'
        ])
        ->make(true);
    }
 
}
