<?php
namespace Modules\Income\Repositories;

use App\Enums\AccountHead;
use App\Enums\StatementType;
use App\Repositories\Upload\UploadInterface;
use Illuminate\Support\Facades\Auth;
use Modules\Account\Entities\BankTransaction;
use Modules\Account\Enums\AccountType;
use Modules\Account\Repositories\AccountInterface;
use Modules\AccountHead\Repositories\AccountHeadInterface;
use Modules\Branch\Repositories\BranchInterface;
use Modules\Income\Entities\Income;
use Modules\Income\Repositories\IncomeInterface;
class IncomeRepository implements IncomeInterface{
 
    protected $model,$upload,$accountHeadRepo,$accountRepo,$branchRepo;
    public function __construct(
            Income $model,
            UploadInterface $upload,
            AccountHeadInterface $accountHeadRepo,
            AccountInterface $accountRepo,
            BranchInterface $branchRepo
        )
    {
        $this->model           = $model;
        $this->upload          = $upload;
        $this->accountHeadRepo = $accountHeadRepo;
        $this->accountRepo     = $accountRepo;
        $this->branchRepo      = $branchRepo;
    }
    public function get(){
        return $this->model::with(['upload','user'])->where('business_id',business_id())->orderByDesc('id')->get();
    }
    public function getFind($id){
        return $this->model::find($id);
    }
    public function store($request){
        try { 
            $head = $this->accountHeadRepo->getFind($request->account_head);
            $income               = new Income();
            $income->business_id  = business_id();
            $income->branch_id    = $request->from_branch;
            $income->account_head_id = $head->id; 
            $income->from_account = $request->from_account;
            $income->to_account   = $request->to_account;
            $income->amount       = $request->amount;
            $income->note         = $head->note;
 
            if($request->document && !blank($request->document)):
                $income->document_id  = $this->upload->upload('income','',$request->document);
            endif;
            $income->created_by   = Auth::user()->id;
            $income->save();  

            $branch               = $this->branchRepo->getFind($request->from_branch);
            $branch->balance      = ($branch->balance + $request->amount);
            $branch->save();
 
            $fromAccount              = $this->accountRepo->getFind($request->from_account);
            $fromAccount->balance     = ($fromAccount->balance - $request->amount);
            $fromAccount->save();

            //branch expense transaction
            $fromBankTransaction              = new BankTransaction(); 
            $fromBankTransaction->income_id   = $income->id;
            $fromBankTransaction->user_type   = AccountType::BRANCH;
            $fromBankTransaction->business_id = business_id();
            $fromBankTransaction->branch_id   = $request->from_branch;
            $fromBankTransaction->account_id  = $request->from_account;
            $fromBankTransaction->type        = StatementType::INCOME;
            $fromBankTransaction->amount      = $request->amount;
            $fromBankTransaction->note        = $head->note;
            if($request->document && !blank($request->document)):
                $fromBankTransaction->document_id  = $income->document_id;
            endif;
            $fromBankTransaction->save();



            //business balance add from branch
            $toAccount              = $this->accountRepo->getFind($request->to_account);
            $toAccount->balance     = ($toAccount->balance + $request->amount);
            $toAccount->save();

            //business income transaction
            $toBankTransaction              = new BankTransaction(); 
            $toBankTransaction->income_id   = $income->id;
            $toBankTransaction->user_type   = AccountType::ADMIN;
            $toBankTransaction->business_id = business_id(); 
            $toBankTransaction->account_id  = $request->to_account;
            $toBankTransaction->type        = StatementType::INCOME;
            $toBankTransaction->amount      = $request->amount;
            $toBankTransaction->note        = $head->note;
            if($request->document && !blank($request->document)):
                $toBankTransaction->document_id  = $income->document_id;
            endif;
            $toBankTransaction->save();



            return true;
        } catch (\Throwable $th) { 
            return false;
        }
    }
    public function update($id,$request){
        try {
            $this->amountRevarse($id);

            $head = $this->accountHeadRepo->getFind($request->account_head);
            $income               = $this->getFind($id);
            $income->business_id  = business_id();
            $income->branch_id    = $request->from_branch;
            $income->account_head_id = $head->id; 
            $income->from_account = $request->from_account;
            $income->to_account   = $request->to_account;
            $income->amount       = $request->amount;
            $income->note         = $head->note;
 
            if($request->document && !blank($request->document)):
                $income->document_id  = $this->upload->upload('income',$income->document_id,$request->document);
            endif;
            $income->created_by   = Auth::user()->id;
            $income->save(); 

            $branch               = $this->branchRepo->getFind($request->from_branch);
            $branch->balance      = ($branch->balance + $request->amount);
            $branch->save();

            $fromAccount              = $this->accountRepo->getFind($request->from_account);
            $fromAccount->balance     = ($fromAccount->balance - $request->amount);
            $fromAccount->save();


            //branch expense transaction
            $fromBankTransaction              = new BankTransaction(); 
            $fromBankTransaction->income_id   = $income->id;
            $fromBankTransaction->user_type   = AccountType::BRANCH;
            $fromBankTransaction->business_id = business_id();
            $fromBankTransaction->branch_id   = $request->from_branch;
            $fromBankTransaction->account_id  = $request->from_account;
            $fromBankTransaction->type        = StatementType::INCOME;
            $fromBankTransaction->amount      = $request->amount;
            $fromBankTransaction->note        = $head->note;
            if($request->document && !blank($request->document)):
                $fromBankTransaction->document_id  = $income->document_id;
            endif;
            $fromBankTransaction->save();
            

            //business balance add from branch
            $toAccount              = $this->accountRepo->getFind($request->to_account);
            $toAccount->balance     = ($toAccount->balance + $request->amount);
            $toAccount->save();

            //business income transaction
            $toBankTransaction              = new BankTransaction(); 
            $toBankTransaction->income_id   = $income->id;
            $toBankTransaction->user_type   = AccountType::ADMIN;
            $toBankTransaction->business_id = business_id(); 
            $toBankTransaction->account_id  = $request->to_account;
            $toBankTransaction->type        = StatementType::INCOME;
            $toBankTransaction->amount      = $request->amount;
            $toBankTransaction->note        = $head->note;
            if($request->document && !blank($request->document)):
                $toBankTransaction->document_id  = $income->document_id;
            endif;
            $toBankTransaction->save();



            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    public function amountRevarse($id){

        $income = $this->getFind($id); 
        $branch               = $this->branchRepo->getFind($income->branch_id); 
        $branch->balance      = ($branch->balance - $income->amount);
        $branch->save();

        $fromAccount              = $this->accountRepo->getFind($income->from_account);
        $fromAccount->balance     = ($fromAccount->balance + $income->amount);
        $fromAccount->save();
 
        $toAccount              = $this->accountRepo->getFind($income->to_account);
        $toAccount->balance     = ($toAccount->balance - $income->amount);
        $toAccount->save(); 
        BankTransaction::where('income_id',$income->id)->delete();
    }
    public function delete($id){
        $income = $this->getFind($id);
        
        $branch               = $this->branchRepo->getFind($income->branch_id); 
        $branch->balance      = ($branch->balance - $income->amount);
        $branch->save();

        $fromAccount              = $this->accountRepo->getFind($income->from_account);
        $fromAccount->balance     = ($fromAccount->balance + $income->amount);
        $fromAccount->save();
 
        $toAccount              = $this->accountRepo->getFind($income->to_account);
        $toAccount->balance     = ($toAccount->balance - $income->amount);
        $toAccount->save(); 
        $this->upload->unlinkImage($income->document_id);
        return $this->model::destroy($id);
    }
}