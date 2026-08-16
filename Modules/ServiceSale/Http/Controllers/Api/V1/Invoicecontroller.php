<?php

namespace Modules\ServiceSale\Http\Controllers\Api\V1;

use App\Traits\ApiReturnFormatTrait;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\ServiceSale\Http\Resources\v1\InvoiceResource;
use Modules\ServiceSale\Repositories\ServiceSaleInterface;

class Invoicecontroller extends Controller
{
    use ApiReturnFormatTrait;
    protected $repo;
    public function __construct(ServiceSaleInterface $repo)
    {
        $this->repo = $repo;
    }
    public function index()
    {
        $service_sales    =  $this->repo->get();
        return $this->responseWithSuccess('Service sale invoice list', [
            'service_sale_invoice_list'  => InvoiceResource::collection($service_sales)
        ],200);
    }
 
}
