<?php

namespace Modules\TaxRate\Http\Controllers\Api\V1;

use App\Traits\ApiReturnFormatTrait;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\TaxRate\Http\Requests\StoreRequest;
use Modules\TaxRate\Http\Resources\v1\TaxRateResource;
use Modules\TaxRate\Repositories\TaxRateInterface;

class TaxRateController extends Controller
{
    use ApiReturnFormatTrait;
   
    protected $repo;
    public function __construct(TaxRateInterface $repo
    )
    {
       $this->repo           = $repo;
    }
   public function index()
   {
       $taxRates = $this->repo->get();
       return $this->responseWithSuccess(__('success'),[
            'tax_rates' => TaxRateResource::collection($taxRates)
       ]);
   }
 
   public function store(StoreRequest $request)
   { 
       if($this->repo->store($request)){ 
            return $this->responseWithSuccess(__('tax_rate_added_successfully'),[],200);
       }else{
         return $this->responseWithError(__('error'),[],400);
       }
   }
 
   public function edit($id)
   { 
       $taxRate      = $this->repo->getFind($id); 
       return $this->responseWithSuccess(__('success'),[
        'tax_rate'  => new TaxRateResource($taxRate)
       ]);
   }

   public function update(StoreRequest $request)
   {
       
       if($this->repo->update($request->id,$request)){ 
           return $this->responseWithSuccess(__('tax_rate_updated_successfully'),[],200);
       }else{
           return $this->responseWithError(__('error'),[],400);
       }
   }

   public function destroy($id)
   {
       
       if($this->repo->delete($id)){ 
           return $this->responseWithSuccess(__('tax_rate_deleted_successfully'),[],200); 
       }else{
           return $this->responseWithError(__('error'),[],400);
       }
   }

   public function statusUpdate($id){
       
       if($this->repo->statusUpdate($id)){ 
            return $this->responseWithSuccess(__('tax_rate_update_successfully'),[],200);  
       }else{
            return $this->responseWithError(__('error'),[],400);
       }
   }

}
