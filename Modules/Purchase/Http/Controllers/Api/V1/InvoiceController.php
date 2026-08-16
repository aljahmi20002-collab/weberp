<?php

namespace Modules\Purchase\Http\Controllers\Api\V1;

use App\Traits\ApiReturnFormatTrait;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Purchase\Http\Resources\v1\InvoiceResource;
use Modules\Purchase\Http\Resources\v1\ReturnInvoiceResource;
use Modules\Purchase\Repositories\PurchaseInterface;
use Modules\Purchase\Repositories\PurchaseReturn\PurchaseReturnInterface;

class InvoiceController extends Controller
{
    use ApiReturnFormatTrait;
    protected $repo,$pReturnRepo;
    public function __construct(PurchaseInterface $repo,PurchaseReturnInterface $pReturnRepo)
    {
        $this->repo        = $repo;
        $this->pReturnRepo = $pReturnRepo;
    }
    public function index()
    {   
        $purchaseInvoices        = $this->repo->getInvoice();
        return $this->responseWithSuccess('Purchase invoice list', [
            'purchase_invoice_list'  =>  InvoiceResource::collection($purchaseInvoices)
        ]);
    }

    public function purchaseReturnIndex()
    {   
        $p_return_invoices        = $this->pReturnRepo->getInvoice();
        return $this->responseWithSuccess('Purchase return invoice list',[
            'purchase_return_invoice_list'  =>  ReturnInvoiceResource::collection($p_return_invoices)
        ]);
    }

}
