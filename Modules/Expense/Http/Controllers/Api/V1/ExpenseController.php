<?php

namespace Modules\Expense\Http\Controllers\Api\V1;

use App\Traits\ApiReturnFormatTrait;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Account\Http\Resources\v1\AccountResource;
use Modules\Account\Repositories\AccountInterface;
use Modules\AccountHead\Repositories\AccountHeadInterface;
use Modules\Branch\Http\Resources\v1\BranchResource;
use Modules\Branch\Repositories\BranchInterface;
use Modules\Expense\Entities\Expense;
use Modules\Expense\Http\Requests\StoreRequest;
use Modules\Expense\Http\Resources\v1\ExpenseResource;
use Modules\Expense\Repositories\ExpenseInterface;

class ExpenseController extends Controller
{
    use ApiReturnFormatTrait;
    protected $repo,$accountHeadRepo,$accountRepo,$branchRepo;
    public function __construct(
        ExpenseInterface $repo,
        AccountHeadInterface $accountHeadRepo,
        AccountInterface     $accountRepo,
        BranchInterface      $branchRepo
   )
   {
        $this->repo              = $repo;
        $this->accountHeadRepo   = $accountHeadRepo;
        $this->accountRepo       = $accountRepo;
        $this->branchRepo        = $branchRepo;
   }
    public function index()
    {
        $expenses    = $this->repo->get(); 
        return $this->responseWithSuccess(__('success'),[
            'expenses'  => ExpenseResource::collection($expenses)
        ],200);
    }
  
    public function create()
    {
        $accountHeads     = $this->accountHeadRepo->getExpenseActiveHead();
        $businessAccounts = $this->accountRepo->getBusinessActiveAccounts();
        $branches         = $this->branchRepo->getAll(business_id());
        
        return $this->responseWithSuccess(__('success'),[
            'account_heads'  => $accountHeads,
            'from_accounts'   => AccountResource::collection($businessAccounts),
            'branch_list'    => BranchResource::collection($branches)  
        ],200);
    }
     
    public function store(StoreRequest $request)
    { 
        if($this->repo->store($request)){ 
            return $this->responseWithSuccess(__('expense_added_successfully'),[],200);
        }else{
             return $this->responseWithError(__('error'),[],400);
        }
    } 
    public function edit($id)
    {
        $expense          = $this->repo->getFind($id); 
        $accountHeads     = $this->accountHeadRepo->getExpenseActiveHead();
        $businessAccounts = $this->accountRepo->getBusinessActiveAccounts();
        $branches         = $this->branchRepo->getAll(business_id());
        $branchAccounts   = $this->accountRepo->getBranchAccounts($expense->branch_id);
        return $this->responseWithSuccess(__('success'),[
            'expense'         => new ExpenseResource($expense),
            'account_heads'   => $accountHeads,
            'from_accounts'   => AccountResource::collection($businessAccounts),
            'branch_list'     => BranchResource::collection($branches),
            'branch_accounts' => AccountResource::collection($branchAccounts)
        ],200);
    }

    public function update(StoreRequest $request)
    { 
        if($this->repo->update($request->id,$request)){ 
            return $this->responseWithSuccess(__('expense_updated_successfully'),[],200);
        }else{
            return $this->responseWithError(__('error'),[],400);
        }
    }
 
    public function destroy($id)
    {
        
        if($this->repo->delete($id)){ 
            return $this->responseWithSuccess(__('expense_deleted_successfully'),[],200);
        }else{
            return $this->responseWithError(__('error'),[],400);
        }
    }

    public function branchAccount($branch_id){
         
        $accounts = $this->accountRepo->getBranchAccounts($branch_id);
        return $this->responseWithSuccess(__('success'),[
            'branch_accounts' => AccountResource::collection($accounts)
        ],200);
    }
    
}
