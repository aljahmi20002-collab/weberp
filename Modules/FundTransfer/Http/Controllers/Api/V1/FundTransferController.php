<?php

namespace Modules\FundTransfer\Http\Controllers\Api\V1;

use App\Traits\ApiReturnFormatTrait;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FundTransfer\Http\Requests\StoreRequest;
use Modules\Account\Http\Resources\v1\AccountResource;
use Modules\Account\Repositories\AccountInterface;
use Modules\FundTransfer\Http\Resources\v1\FundTransferResource;
use Modules\FundTransfer\Repositories\FundTransferInterface;

class FundTransferController extends Controller
{
    use ApiReturnFormatTrait;
    protected $repo,$accountRepo;
    public function __construct(FundTransferInterface $repo,AccountInterface $accountRepo)
    {
        $this->repo        = $repo;
        $this->accountRepo = $accountRepo;
    }
 
    public function index()
    { 
        $fund_transfers    = $this->repo->get(); 
        return $this->responseWithSuccess(__('success'),[
            'fund_transfers' => FundTransferResource::collection($fund_transfers)
        ],200);
    }
  

    public function create()
    {
        $accounts    = $this->accountRepo->getAdminActiveAccount();
        return $this->responseWithSuccess(__('success'),[
            'accounts' => AccountResource::collection($accounts)
        ],200);
    }
 
    public function store(StoreRequest $request)
    {
       
        $account   = $this->accountRepo->getFind($request->from_account);
        if($account->balance < $request->amount): 
            return $this->responseWithError(__('not_enough_balance'),[],400);
        endif;

        if($request->amount <= 0): 
            return $this->responseWithError(__('more_than_0_amount'),[],400);
        endif; 
        if($this->repo->store($request)){ 
            return $this->responseWithSuccess(__('fund_transfered_successfully'),[],200);
        }else{
            return $this->responseWithError(__('error'),[],400);
        }
    }
  
    public function edit($id)
    {
        $accounts       = $this->accountRepo->getAdminActiveAccount();
        $fund_transfer  = $this->repo->getFind($id);
        return $this->responseWithSuccess(__('success'),[
            'accounts'      => AccountResource::collection($accounts),
            'fund_transfer' => new FundTransferResource($fund_transfer)
        ],200);
    }

    public function update(StoreRequest $request)
    { 
        $account       = $this->accountRepo->getFind($request->from_account);
        $fund_transfer = $this->repo->getFind($request->id);
        $balance       = ($account->balance + $fund_transfer->amount);
        if($balance < $request->amount): 
            return $this->responseWithError(__('not_enough_balance'),[],400);
        endif;

        if($request->amount <= 0): 
            return $this->responseWithError(__('more_than_0_amount'),[],400);
        endif;

        if($this->repo->update($request->id,$request)){ 
            return $this->responseWithSuccess(__('fund_transfered_update_successfully'),[],200);
        }else{ 
            return $this->responseWithError(__('error'),[],400);
        }
    }
 
    public function destroy($id)
    { 
        if($this->repo->delete($id)){ 
            return $this->responseWithSuccess(__('fund_transfer_deleted_successfully'),[],200);
        }else{
            return $this->responseWithError(__('error'),[],400);
        }
    }
}
