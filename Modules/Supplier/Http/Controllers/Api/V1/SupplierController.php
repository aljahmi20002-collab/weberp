<?php

namespace Modules\Supplier\Http\Controllers\Api\V1;

use App\Traits\ApiReturnFormatTrait;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Supplier\Http\Resources\v1\SupplierResource;
use Modules\Supplier\Repositories\SupplierInterface; 

class SupplierController extends Controller
{ 
    use ApiReturnFormatTrait;
    protected $repo;
    public function __construct(SupplierInterface $repo)
    {
        $this->repo = $repo;
    }
    public function index()
    {
        $suppliers = $this->repo->getAll();
        return $this->responseWithSuccess('Supplier list',[
            'supplier_list'=> SupplierResource::collection($suppliers)
        ],200);
    }
 
}
