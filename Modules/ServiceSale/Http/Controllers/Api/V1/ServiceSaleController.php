<?php

namespace Modules\ServiceSale\Http\Controllers\Api\V1;

use App\Traits\ApiReturnFormatTrait;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;
use Modules\Branch\Repositories\BranchInterface;
use Modules\Customer\Repositories\CustomerInterface;
use Modules\ServiceSale\Repositories\ServiceSaleInterface;
use Modules\TaxRate\Repositories\TaxRateInterface;
use Illuminate\Support\Str;
use Modules\Branch\Http\Resources\v1\BranchResource;
use Modules\Customer\Enums\CustomerType;
use Modules\Customer\Http\Resources\v1\CustomerResource;
use Modules\Service\Http\Resources\v1\ServiceResource;
use Modules\ServiceSale\Http\Requests\StoreRequest;
use Modules\ServiceSale\Http\Resources\v1\ServiceSaleResource;
use Modules\ServiceSale\Traits\ServiceSaleTrait;
use Modules\TaxRate\Http\Resources\v1\TaxRateResource;

class ServiceSaleController extends Controller
{
    use ApiReturnFormatTrait,ServiceSaleTrait;
    protected $branchRepo,$customerRepo,$taxRepo,$repo;
    public function __construct(
            BranchInterface $branchRepo,
            CustomerInterface $customerRepo,
            TaxRateInterface $taxRepo,
            ServiceSaleInterface $repo
        )
    {
        $this->branchRepo   = $branchRepo;
        $this->customerRepo = $customerRepo;
        $this->taxRepo      = $taxRepo;
        $this->repo         = $repo;
    }
    
    public function index()
    {
        $sales   = $this->repo->get(); 
        return $this->responseWithSuccess(__('success'),[
            'service_sale_list' => ServiceSaleResource::collection($sales)
        ]);
    }
  
    public function create()
    {
        $branches      = $this->branchRepo->getBranches(business_id());
        $customers     = $this->customerRepo->getActiveCustomers(business_id());
        $taxRates      = $this->taxRepo->getActive(business_id());
        $customer_types  = $this->customerTypes();
        $shipping_status = $this->ShippingStatusCollection();
 
        return $this->responseWithSuccess(__('success'),[
            'branches'       => BranchResource::collection($branches),
            'customer_types' => $customer_types,
            'customers'      => CustomerResource::collection($customers),
            'shipping_status'=>$shipping_status,
            'tax_rates'      => TaxRateResource::collection($taxRates)
        ],200);
    }
 
     //service details find 
     public function serviceFind(Request $request){
         
        $serviceItems = $this->repo->serviceItemsFind($request);  
        return $this->responseWithSuccess(__('success'),[ 
            'service_list'=>ServiceResource::collection($serviceItems)
        ],200);
 
    }
    
    public function serviceDetails(Request $request){
        $service  = $this->repo->serviceItem($request); 
        return $this->responseWithSuccess(__('success'),[ 
            'service_details'    => new ServiceResource($service), 
        ],200);

    }

    public function getTaxrate($id){ 
        $tax     = $this->taxRepo->getFind($id);
        if($tax):
            $rate = $tax->tax_rate;
        else:
            $rate = 0;
        endif;
        return $this->responseWithSuccess(__('success'),[ 
            'tax_rate'    => $rate, 
        ],200);
       
    }
 
    public function store(StoreRequest $request)
    { 
        if($this->repo->store($request)): 
             return $this->responseWithSuccess(__('service_sale_store_successfully'),[],200);
        else:
            return $this->responseWithSuccess(__('error'),[],400);
        endif;
    }
       
    public function edit($id)
    {
        $sale          = $this->repo->getFind($id);
        $branches      = $this->branchRepo->getBranches(business_id());
        $customers     = $this->customerRepo->getActiveCustomers(business_id());
        $taxRates      = $this->taxRepo->getActive(business_id());
        return $this->responseWithSuccess(__('success'),[
            'sale_details' => new ServiceSaleResource($sale),
            'branches'     => BranchResource::collection($branches),
            'customers'    => CustomerResource::collection($customers),
            'tax_rates'    => TaxRateResource::collection($taxRates)
        ],200);
    }
 
    public function update(StoreRequest $request)
    { 
        if($this->repo->update($request->id,$request)): 
            return $this->responseWithSuccess(__('service_sale_update_successfully'),[],200);
        else: 
            return $this->responseWithSuccess(__('error'),[],400);
        endif;
    }
 
    public function destroy($id)
    { 
        if($this->repo->delete($id)): 
            return $this->responseWithSuccess(__('servicesale_delete_successfully'),[],200);
        else: 
            return $this->responseWithSuccess(__('error'),[],400);
        endif;
    }

    public function details($id){
        $sale    = $this->repo->getFind($id);
        return $this->responseWithSuccess(__('success'),[
            'sale_details' => new ServiceSaleResource($sale)
        ],200);
    }

 

}
