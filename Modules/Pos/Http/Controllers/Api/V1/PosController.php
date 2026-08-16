<?php

namespace Modules\Pos\Http\Controllers\Api\V1;

use App\Traits\ApiReturnFormatTrait;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Branch\Http\Resources\v1\BranchResource;
use Modules\Branch\Repositories\BranchInterface;
use Modules\Brand\Http\Resources\v1\BrandResource;
use Modules\Brand\Repositories\BrandInterface;
use Modules\Category\Http\Resources\v1\CategoryResource;
use Modules\Category\Repositories\CategoryInterface;
use Modules\Customer\Http\Resources\v1\CustomerResource;
use Modules\Customer\Repositories\CustomerInterface;
use Modules\Pos\Http\Requests\StoreRequest;
use Modules\Pos\Http\Resources\v1\PosResource;
use Modules\Pos\Http\Resources\v1\VariationLocationDetailsResource;
use Modules\Pos\Repositories\PosInterface;
use Modules\Product\Entities\VariationLocationDetails;
use Modules\TaxRate\Entities\TaxRate;
use Modules\TaxRate\Http\Resources\v1\TaxRateResource;
use Modules\TaxRate\Repositories\TaxRateInterface;

class PosController extends Controller
{
    use ApiReturnFormatTrait;
   protected $repo,$branchRepo,$categoryRepo,$brandRepo,$customerRepo,$taxRateRepo;
   public function __construct(
        PosInterface        $repo, 
        BranchInterface     $branchRepo,
        CategoryInterface   $categoryRepo,
        BrandInterface      $brandRepo,
        CustomerInterface   $customerRepo,
        TaxRateInterface    $taxRateRepo
    )
   {
        $this->repo         = $repo;
        $this->branchRepo   = $branchRepo;
        $this->categoryRepo = $categoryRepo;
        $this->brandRepo    = $brandRepo;
        $this->customerRepo = $customerRepo;
        $this->taxRateRepo  = $taxRateRepo;
   }

   public function index(){
        $poses  = PosResource::collection($this->repo->getAllPos());  
        return $this->responseWithSuccess('POS list', ['pos_list'=>$poses], 200);
   }


   public function create(Request $request){
        $branches      = BranchResource::collection($this->branchRepo->getBranches(business_id()));
        $categories    = CategoryResource::collection($this->categoryRepo->get());
        $brands        = BrandResource::collection($this->brandRepo->get());
        $customers     = CustomerResource::collection($this->customerRepo->getActiveCustomers(business_id()));
 
        $data = [
                'branches'      => $branches,
                'categories'    => $categories,
                'brands'        => $brands,
                'customers'     => $customers, 
        ];

        return $this->responseWithSuccess('POS list', $data, 200);
   }

   public function productItems(Request $request){
        $variationLocationDetails =  VariationLocationDetails::with('product', 'ProductVariation', 'variation')->where(function ($query) use ($request) {
            $query->where('business_id', business_id());
            if (business()) :
                if ($request->branch_id) :
                    $query->where('branch_id', $request->branch_id);
                endif;
            else :
                $query->where('branch_id', Auth::user()->branch_id);
            endif;
            $query->where(function ($query) use ($request) {
                if ($request->category_id || $request->brand_id) :
                    $query->whereHas('product', function ($query) use ($request) {
                        if (!blank($request->category_id)) :
                            $query->where('category_id', $request->category_id);
                        endif;
                        if (!blank($request->brand_id)) :
                            $query->Where('brand_id', $request->brand_id);
                        endif;
                    });
                endif;
            });
            if($request->search):
                $query->where(function($query){
                    $query->whereHas('ProductVariation',function($query){
                        $query->where('sub_sku','like','%'.request()->search.'%'); 
                    });
                    $query->orWhereHas('product',function($query){
                        $query->where('name','like','%'.request()->search.'%'); 
                        $query->orWhere('sku','like','%'.request()->search.'%'); 
                    });
                });
            endif;
        })->orderByDesc('id')->paginate(10);

        $variation_location_list = VariationLocationDetailsResource::collection($variationLocationDetails);
        return $this->responseWithSuccess('Product item list.', ['item_list' => $variation_location_list], 200);
   }


    public function pricing(Request $request){
     
        $variation_locations = json_decode($request->variation_locations);
        $variation_locations_array=[]; 
        foreach ($variation_locations?? [] as $key => $item) {
            $variation_locations_array[$key]    = (array) $item;
       }
       $request['variation_locations'] = $variation_locations_array; 

       //calculation
        $calculation=[]; 
        $calculation['total_tax_amount']      = 0;
        $calculation['total_price']           = 0; 

        foreach ($variation_locations as $key => $variation_location) { 
            $variation_location = (array) $variation_location;  
            $calculation['total_price'] +=  $variation_location['unit_price'];
        }

        if($request->tax_id): 
            $taxRate      = TaxRate::find($request->tax_id); 
            $taxPerAmount = ($calculation['total_price'] / 100);
            if($taxRate->tax_rate > 0):
                $taxAmount = ($taxPerAmount / $taxRate->tax_rate);
            else:
                $taxAmount = 0;
            endif;
            $calculation['total_tax_amount']  = $taxAmount; 
        endif;

        $calculation['shipping_charge']       =  $request->shipping_charge?? 0; 
        $calculation['discount_amount']       =  $request->discount_amount?? 0; 
        $calculation['total_sell_price']      =  ($calculation['total_price'] + $calculation['shipping_charge'] + $calculation['total_tax_amount']);
        $calculation['total_sell_price']      =  ($calculation['total_sell_price'] - $calculation['discount_amount']);
       //end calculation  
       return $this->responseWithSuccess('Pricing Calculation.', $calculation, 200);
    }
 
    public function store(StoreRequest $request)
    {
     
         $variation_locations = json_decode($request->variation_locations);
         $variation_locations_array=[]; 
         foreach ($variation_locations?? [] as $key => $item) {
             $variation_locations_array[$key]    = (array) $item;
        }
 
        $request['variation_locations'] = $variation_locations_array; 
 
        if($pos=$this->repo->store($request)){
            return $this->responseWithSuccess('POS Created successfully.', ['pos'=>new PosResource($pos)], 200);
        } else {
            return $this->responseWithError(__('error_msg'), [], 200);
        } 
    }
 
}
