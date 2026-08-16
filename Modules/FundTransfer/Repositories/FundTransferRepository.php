<?php
namespace Modules\FundTransfer\Repositories;

use App\Enums\StatementType;
use App\Enums\UserType;
use Carbon\Carbon;
use Modules\Account\Entities\Account;
use Modules\Account\Entities\BankTransaction;
use Modules\Account\Enums\AccountType;
use Modules\FundTransfer\Entities\FundTransfer;

class FundTransferRepository implements FundTransferInterface{
    protected $model;
    public function __construct(FundTransfer $model)
    {
        $this->model = $model;
    }
    public function get(){
        return $this->model::where('business_id',business_id())->orderByDesc('id')->get();
    }
    public function getFind($id){
        return $this->model::find($id);
    }
    public function store($request){
        try {

            $fundTransfer               = new FundTransfer();
            $fundTransfer->business_id  = business_id();
            $fundTransfer->from_account = $request->from_account;
            $fundTransfer->to_account   = $request->to_account; 
            $fundTransfer->amount       = $request->amount;
            $fundTransfer->description  = $request->description;
            $fundTransfer->save();

            $fromAccount             = Account::find($fundTransfer->from_account);
            $fromAccount->balance    = ($fromAccount->balance - $request->amount);
            $fromAccount->save();

            $bankTransaction              = new BankTransaction();
            $bankTransaction->user_type   = AccountType::ADMIN;
            $bankTransaction->business_id = business_id();   
            $bankTransaction->fund_transfer_id = $fundTransfer->id;
            $bankTransaction->account_id  = $fundTransfer->from_account;
            $bankTransaction->type        = StatementType::EXPENSE;
            $bankTransaction->amount      = $fundTransfer->amount;
            $bankTransaction->note        = 'fund_transfered_successfully';
            $bankTransaction->save();

            $toAccount             = Account::find($request->to_account);
            $toAccount->balance    = ($toAccount->balance + $request->amount);
            $toAccount->save();

            $bankTransaction              = new BankTransaction();
            $bankTransaction->user_type   = AccountType::ADMIN;
            $bankTransaction->business_id = business_id();   
            $bankTransaction->fund_transfer_id = $fundTransfer->id;
            $bankTransaction->account_id  = $toAccount->id;
            $bankTransaction->type        = StatementType::INCOME;
            $bankTransaction->amount      = $fundTransfer->amount;
            $bankTransaction->note        = 'fund_transfered_received_successfully';
            $bankTransaction->save();

            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
    public function update($id,$request){
        try {

            $fundTransfer               = $this->getFind($id);
            $this->fundTransferReverse($id);
            $fundTransfer->business_id  = business_id();
            $fundTransfer->from_account = $request->from_account;
            $fundTransfer->to_account   = $request->to_account; 
            $fundTransfer->amount       = $request->amount;
            $fundTransfer->description  = $request->description;
            $fundTransfer->save();

            $fromAccount             = Account::find($fundTransfer->from_account);
            $fromAccount->balance    = ($fromAccount->balance - $request->amount);
            $fromAccount->save();

            $bankTransaction              = new BankTransaction();
            $bankTransaction->user_type   = AccountType::ADMIN;
            $bankTransaction->business_id = business_id();   
            $bankTransaction->fund_transfer_id = $fundTransfer->id;
            $bankTransaction->account_id  = $fundTransfer->from_account;
            $bankTransaction->type        = StatementType::EXPENSE;
            $bankTransaction->amount      = $fundTransfer->amount;
            $bankTransaction->note        = 'fund_transfered_successfully';
            $bankTransaction->save();

            $toAccount             = Account::find($request->to_account);
            $toAccount->balance    = ($toAccount->balance + $request->amount);
            $toAccount->save();

            $bankTransaction              = new BankTransaction();
            $bankTransaction->user_type   = AccountType::ADMIN;
            $bankTransaction->business_id = business_id();   
            $bankTransaction->fund_transfer_id = $fundTransfer->id;
            $bankTransaction->account_id  = $toAccount->id;
            $bankTransaction->type        = StatementType::INCOME;
            $bankTransaction->amount      = $fundTransfer->amount;
            $bankTransaction->note        = 'fund_transfered_received_successfully';
            $bankTransaction->save();
            
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    public function fundTransferReverse($id){
        $fundTransfer   = $this->getFind($id);

        $fromAccount             = Account::find($fundTransfer->from_account);
        $fromAccount->balance    = ($fromAccount->balance + $fundTransfer->amount);
        $fromAccount->save();

        $toAccount             = Account::find($fundTransfer->to_account);
        $toAccount->balance    = ($toAccount->balance - $fundTransfer->amount);
        $toAccount->save(); 
        BankTransaction::where('fund_transfer_id',$id)->delete();
    }
    
    public function delete($id){
        $fundTransfer   = $this->getFind($id);

        $fromAccount             = Account::find($fundTransfer->from_account);
        $fromAccount->balance    = ($fromAccount->balance + $fundTransfer->amount);
        $fromAccount->save();

        $toAccount             = Account::find($fundTransfer->to_account);
        $toAccount->balance    = ($toAccount->balance - $fundTransfer->amount);
        $toAccount->save(); 
        
        return $this->model::destroy($id);
    }
}