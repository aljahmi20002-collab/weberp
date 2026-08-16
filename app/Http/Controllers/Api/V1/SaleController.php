<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sale\StoreSaleRequest;
use App\Traits\ApiReturnFormatTrait;
use Illuminate\Http\Request;
use App\Http\Resources\Api\V1\SaleResource;
use Modules\Branch\Http\Resources\v1\BranchResource;
use Modules\Branch\Repositories\BranchInterface;
use Modules\Customer\Http\Resources\v1\CustomerResource;
use Modules\Customer\Repositories\CustomerInterface;
use Modules\Product\Http\Resources\v1\ApiProductResource;
use Modules\Product\Repositories\ProductInterface;
use Modules\Sell\Repositories\SaleInterface;
use Modules\TaxRate\Http\Resources\v1\TaxRateResource;
use Modules\TaxRate\Repositories\TaxRateInterface;

class SaleController extends Controller
{
    use ApiReturnFormatTrait;

    protected $repo, $taxRepo, $customerRepo, $branchRepo, $productRepo;
    public function __construct(
        SaleInterface $repo,
        TaxRateInterface $taxRepo,
        CustomerInterface $customerRepo,
        BranchInterface $branchRepo,
        ProductInterface $productRepo
    ) {
        $this->repo          = $repo;
        $this->taxRepo          = $taxRepo;
        $this->customerRepo          = $customerRepo;
        $this->branchRepo          = $branchRepo;
        $this->productRepo          = $productRepo;
    }

    public function index()
    {
        $sale_lists  = SaleResource::collection($this->repo->getAllSale());
        return $this->responseWithSuccess(__('success'), [
            'sale_lists' => $sale_lists
        ], 200);
    }

    public function store(StoreSaleRequest $request)
    {
        if ($this->repo->store($request)) {
            return $this->responseWithSuccess(__('sale_store_successfully'), [], 200);
        } else {
            return $this->responseWithError(__('error_msg'), [], 200);
        }
    }

    public function show($id)
    {
        $sale = new SaleResource($this->repo->getFind($id));
        return $this->responseWithSuccess(__('success'), [
            'sale' => $sale,
        ], 200);
    }


    public function update(StoreSaleRequest $request, $id)
    {
        if ($this->repo->update($id, $request)) {
            return $this->responseWithSuccess(__('sale_update_successfully'), [], 200);
        } else {
            return $this->responseWithError(__('error_msg'), [], 200);
        }
    }

    public function destroy($id)
    {
        if ($this->repo->delete($id)) :
            return $this->responseWithSuccess(__('sale_delete_successfully'), [], 200);
        else :
            return $this->responseWithError(__('error_msg'), [], 200);
        endif;
    }

    public function customers()
    {
        $customers = $this->customerRepo->getActiveCustomers(business_id());
        return $this->responseWithSuccess(__('success'), [
            'customers' => CustomerResource::collection($customers)
        ], 200);
    }

    public function branchList($business_id)
    {
        $branch_list = $this->branchRepo->getAllBranch($business_id);
        return $this->responseWithSuccess(__('success'), [
            'branch_list' => BranchResource::collection($branch_list)
        ], 200);
    }

    public function branchWiseProducts($branch_id)
    {
        $product_list = $this->productRepo->branchWiseProducts($branch_id);
        return $this->responseWithSuccess(__('success'), [
            'product_list' => ApiProductResource::collection($product_list)
        ], 200);
    }

    public function taxList()
    {
        $taxList = $this->taxRepo->getTaxRate();
        return $this->responseWithSuccess(__('success'), [
            'tax_list' => TaxRateResource::collection($taxList)
        ], 200);
    }

    public function getTax($id)
    {
        $tax = $this->taxRepo->getFind($id);
        return $this->responseWithSuccess(__('success'), [
            'tax' => new TaxRateResource($tax)
        ], 200);
    }
}
