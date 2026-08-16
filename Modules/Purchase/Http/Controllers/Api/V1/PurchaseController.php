<?php

namespace Modules\Purchase\Http\Controllers\Api\V1;

use App\Traits\ApiReturnFormatTrait;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Purchase\Http\Resources\v1\PurchaseResource;
use Modules\Purchase\Repositories\PurchaseInterface;

class PurchaseController extends Controller
{
    use ApiReturnFormatTrait;
    protected $repo;
    public function __construct(PurchaseInterface $repo)
    {
        $this->repo = $repo;
    }
 
    public function index()
    {
        $purchases   = $this->repo->getAllPurchase(); 
        return $this->responseWithSuccess(['Purchase list',
            'purchase_list'=> PurchaseResource::collection($purchases)
        ],200);
    }

}
