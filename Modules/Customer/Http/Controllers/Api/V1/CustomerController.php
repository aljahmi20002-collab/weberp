<?php

namespace Modules\Customer\Http\Controllers\Api\V1;

use App\Enums\Status;
use App\Traits\ApiReturnFormatTrait;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Customer\Http\Resources\v1\CustomerResource;
use Modules\Customer\Repositories\CustomerInterface;
use Modules\Customer\Http\Requests\StoreRequest;
class CustomerController extends Controller
{
    use ApiReturnFormatTrait;
    protected $repo;
    public function __construct(CustomerInterface $repo)
    {
        $this->repo = $repo;
    }
    public function index()
    {
        $customers = $this->repo->getAllCustomers();
        return $this->responseWithSuccess('Customer List',[
            'customer_list'=> CustomerResource::collection($customers)
        ],200);
    }


    public function store(StoreRequest $request){
        $request['status'] = 'on';
        if ($this->repo->store($request)) : 
           return $this->responseWithSuccess(__('customer_store_successfully'),[],200);
        else : 
            return $this->responseWithSuccess(__('error'),[],400);
        endif;
    }
 
}
