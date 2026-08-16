<?php

namespace Modules\Product\Http\Controllers\Api\V1;

use App\Traits\ApiReturnFormatTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\Branch\Repositories\BranchInterface;
use Modules\Brand\Repositories\BrandInterface;
use Modules\Business\Repositories\BusinessInterface;
use Modules\BusinessSettings\Repositories\BarcodeSettings\BarcodeSettingsInterface;
use Modules\Category\Repositories\CategoryInterface;
use Modules\Product\Http\Requests\StoreRequest;
use Modules\Product\Http\Resources\v1\ProductDetailsResource;
use Modules\Product\Http\Resources\v1\ProductResource;
use Modules\Product\Repositories\ProductInterface;
use Modules\Product\Traits\BusinessWiseDataFetch;
use Modules\TaxRate\Repositories\TaxRateInterface;
use Modules\Unit\Repositories\UnitInterface;
use Modules\Variation\Repositories\VariationInterface;
use Modules\Warranties\Repositories\WarrantyInterface;

class ProductController extends Controller
{
    use ApiReturnFormatTrait;
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
        return $this->responseWithSuccess(__('success'), ['products'=>  ProductResource::collection($this->repo->getAllProducts())], 200);
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
        $barcodeTypes = \Config::get('pos_default.barcode_types');
        return $this->responseWithSuccess(__('success'), ['barcodeTypes'=>$barcodeTypes,'units'=>$units,'categories'=>$categories,'taxRates'=>$taxRates,'brands'=>$brands,'branches'=>$branches,'variations'=>$variations,'business'=>$business,'warranties'=>$warranties  ], 200);

    }

    public function store(Request $request)
    {
        $validator = new StoreRequest();
        $validator = Validator::make($request->all(), $validator->rules());

        if ($validator->fails()) {
            return $this->responseWithError(__('error'), ['message' => $validator->errors()], 422);
        }

        if($this->repo->store($request)){
            return $this->responseWithSuccess(__('product_added_successfully'), [], 200);
        }else{
            return $this->responseWithError(__('errors'), [], 500);
        }
    }

    public function view($id)
    {
        $product   = $this->repo->getFind($id);
        if($product) {
            return $this->responseWithSuccess(__('success'), ['product'=> new ProductDetailsResource($product)], 200);
        }else {
            return $this->responseWithError(__('errors'), [], 500);
        }    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        $product   = $this->repo->getFind($id);
        if($product) {
            return $this->responseWithSuccess(__('success'), ['product'=> new ProductResource($product)], 200);
        }else {
            return $this->responseWithError(__('errors'), [], 500);
        }

    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $validator = new StoreRequest();
        $validator = Validator::make($request->all(), $validator->rules());

        if ($validator->fails()) {
            return $this->responseWithError(__('error'), ['message' => $validator->errors()], 422);
        }

        if($this->repo->update($request->id,$request)){
            return $this->responseWithSuccess(__('product_updated_successfully'), [], 200);
        }else{
            return $this->responseWithError(__('errors'), [], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        if($this->repo->delete($id)){
            return $this->responseWithSuccess(__('product_delete_successfully'), [], 200);
        }else{
            return $this->responseWithError(__('errors'), [], 500);
        }
    }

    public function statusUpdate($id){ 
        if($this->repo->statusUpdate($id)){
            return $this->responseWithSuccess(__('product_updated_successfully'), [], 200);
        }else{
            return $this->responseWithError(__('errors'), [], 500);
        }
    }

   public function categoryWiseProducts($category_id){
    return $this->responseWithSuccess(__('success'), ['products'=>  ProductResource::collection($this->repo->getCategoryWiseProducts($category_id))], 200);
   
   }
    public function subcategoryWiseProducts($category_id,$subcategory_id){
        return $this->responseWithSuccess(__('success'), ['products'=>  ProductResource::collection($this->repo->getSubCategoryWiseProducts($category_id,$subcategory_id))], 200);
    }
}
