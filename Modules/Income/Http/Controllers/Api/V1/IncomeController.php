<?php

namespace Modules\Income\Http\Controllers\Api\V1;

use App\Traits\ApiReturnFormatTrait;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Account\Http\Resources\v1\AccountResource;
use Modules\Account\Repositories\AccountInterface;
use Modules\AccountHead\Repositories\AccountHeadInterface;
use Modules\Branch\Http\Resources\v1\BranchResource;
use Modules\Branch\Repositories\BranchInterface;
use Modules\Income\Http\Requests\StoreRequest;
use Modules\Income\Http\Resources\v1\IncomeResource;
use Modules\Income\Repositories\IncomeInterface;

class IncomeController extends Controller
{
     use ApiReturnFormatTrait;
    
    protected $repo,$accountRepo,$branchRepo,$accountHeadRepo;
    public function __construct(
            IncomeInterface $repo,
            AccountInterface $accountRepo,
            BranchInterface $branchRepo,
            AccountHeadInterface $accountHeadRepo
        )
    {
        $this->repo            = $repo;
        $this->accountRepo     = $accountRepo;
        $this->branchRepo      = $branchRepo;
        $this->accountHeadRepo = $accountHeadRepo;
    }
    public function index()
    {
        $incomes   = $this->repo->get();
        return $this->responseWithSuccess(__('success'),[
            'incomes'  => IncomeResource::collection($incomes)
        ],200);
    }
 
    public function create()
    {
        $accountHeads   = $this->accountHeadRepo->getIncomeActiveHead();
        $accounts       = $this->accountRepo->getBusinessActiveAccounts();
        $branches       = $this->branchRepo->getAll(business_id());
       return $this->responseWithSuccess(__('success'),[
        'account_heads' => $accountHeads,
        'to_accounts'   => AccountResource::collection($accounts),
        'branch_list'   => BranchResource::collection($branches)
       ],200);
    }

    public function store(StoreRequest $request)
    {
     
        if($this->repo->store($request)){ 
            return $this->responseWithSuccess(__('income_added_successfully'),[],200);
        }else{ 
            return $this->responseWithError(__('error'),[],400);
        }
    }

    public function edit($id)
    {
        $accountHeads   = $this->accountHeadRepo->getIncomeActiveHead();
        $accounts       = $this->accountRepo->getBusinessActiveAccounts();
        $branches       = $this->branchRepo->getAll(business_id());
        $income         = $this->repo->getFind($id);
        $branchAccounts = $this->accountRepo->getBranchAccounts($income->branch_id);
 
        return $this->responseWithSuccess(__('success'),[
            'account_heads'    => $accountHeads,
            'income'           => new IncomeResource($income),
            'branche_list'     => BranchResource::collection($branches),
            'branch_accounts'  => AccountResource::collection($branchAccounts),
            'to_accounts'      => AccountResource::collection($accounts)
        ],200);
       
    }
 
    public function update(StoreRequest $request)
    { 
        if($this->repo->update($request->id,$request)){ 
           return $this->responseWithSuccess(__('income_update_successfully'),[],200);
        }else{
            return $this->responseWithSuccess(__('error'),[],400);
        }
    }
 
    public function destroy($id)
    { 
        if($this->repo->delete($id)){ 
            return $this->responseWithSuccess(__('income_deleted_successfully'),[],200);
        }else{
            return $this->responseWithSuccess(__('error'),[],400);
        }
    }
 
    public function branchAccount($branch_id){ 
        $branch_accounts = $this->accountRepo->getBranchAccounts($branch_id); 
        return $this->responseWithSuccess(__('success'),[
            'branch_accounts' => AccountResource::collection($branch_accounts)
        ],200);
    }

}
