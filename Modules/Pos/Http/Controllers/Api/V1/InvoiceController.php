<?php

namespace Modules\Pos\Http\Controllers\Api\V1;

use App\Traits\ApiReturnFormatTrait;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Pos\Http\Resources\v1\InvoiceResource;
use Modules\Pos\Repositories\PosInterface;

class InvoiceController extends Controller
{
    use ApiReturnFormatTrait;
    protected $posRepo;
    public function __construct(PosInterface $posRepo)
    {
        $this->posRepo = $posRepo;
    }
    public function getInvoice(){
        $posinvoices            = $this->posRepo->getInvoice();
        return $this->responseWithSuccess(__('success'),[
            'pos_invoice_list'   => InvoiceResource::collection($posinvoices)
        ],200);

    }
}
