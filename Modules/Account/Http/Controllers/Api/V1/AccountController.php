<?php

namespace Modules\Account\Http\Controllers\Api\V1;

use App\Traits\ApiReturnFormatTrait;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Modules\Account\Http\Requests\StoreRequest;
use Modules\Account\Http\Resources\v1\AccountResource;
use Modules\Account\Http\Resources\v1\BankTransactionResource;
use Modules\Account\Repositories\AccountInterface; 

class AccountController extends Controller
{
    use ApiReturnFormatTrait;
    
    protected $repo;
    public function __construct(AccountInterface $repo)
    {
        $this->repo    = $repo;
    }
    public function index()
    {  
        $accounts  = $this->repo->getAllAccounts();
        return $this->responseWithSuccess(__('success'),[
            'accounts'=> AccountResource::collection($accounts)
        ]);
    }
      
    public function store(StoreRequest $request)
    { 
        if($this->repo->store($request)){
          return $this->responseWithSuccess(__('account_added_successfully'),[],200);
        }else{
             return $this->responseWithError(__('error'),[],400);
        }
    }

    public function makeDefault($id){
    
        if($this->repo->makeDefault($id)){ 
            return $this->responseWithSuccess(__('account_update_successfully'),[],200);
        }else{
            return $this->responseWithError(__('error'),[],400);
        }
    }
    public function edit($id)
    {
        $account = $this->repo->getFind($id);
        return $this->responseWithSuccess(__('success'),[
            'account'=>new AccountResource($account)
        ],200);
    } 
    public function update(StoreRequest $request)
    {
        
        if($this->repo->update($request->id,$request)){ 
            return $this->responseWithSuccess(__('account_update_successfully'),[],200);
        }else{ 
            return $this->responseWithError(__('error'),[],400);
        }
    }
    public function destroy($id)
    {
        
        if($this->repo->delete($id)){ 
            return $this->responseWithSuccess(__('account_deleted_successfully'),[],200);
        }else{
            return $this->responseWithError(__('error'),[],400);
        }
    } 
    public function statusUpdate($id){
       
        if($this->repo->statusUpdate($id)){ 
            return $this->responseWithSuccess(__('account_status_update_successfully'),[],200);
        }else{
            return $this->responseWithError(__('error'),[],400);
        }
    }

   
    
    public function getbankTransaction(){
        $bank_transactions    = $this->repo->getBankTransactions();  
        return $this->responseWithSuccess(__('success'),[
            'bank_transactions' => BankTransactionResource::collection($bank_transactions)
        ]);
    }

}
