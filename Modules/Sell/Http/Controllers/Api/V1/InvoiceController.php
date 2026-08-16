<?php

namespace Modules\Sell\Http\Controllers\Api\V1;

use App\Traits\ApiReturnFormatTrait;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Sell\Http\Resources\v1\InvoiceResource;
use Modules\Sell\Repositories\SaleInterface;

class InvoiceController extends Controller
{
    use ApiReturnFormatTrait;
    protected $repo;
    public function __construct(SaleInterface $repo)
    {
        $this->repo = $repo;
    }
    public function index()
    {

        $sales = $this->repo->getInvoice();
       return $this->responseWithSuccess('Sale invoice list',[
            'sal_invoice_list' => InvoiceResource::collection($sales)
       ],200);
    }

 
}
