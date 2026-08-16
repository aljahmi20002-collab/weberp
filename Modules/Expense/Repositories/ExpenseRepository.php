<?php
namespace Modules\Expense\Repositories;
use App\Enums\StatementType;
use App\Repositories\Upload\UploadInterface;
use Illuminate\Support\Facades\Auth;
use Modules\Account\Entities\BankTransaction;
use Modules\Account\Enums\AccountType;
use Modules\Account\Repositories\AccountInterface;
use Modules\AccountHead\Repositories\AccountHeadInterface;
use Modules\Branch\Repositories\BranchInterface;
use Modules\Expense\Entities\Expense;

class ExpenseRepository implements ExpenseInterface{
    protected $model,$accountHeadRepo,$upload,$branchRepo,$accountRepo;
    public function __construct(
            Expense $model,
            AccountHeadInterface $accountHeadRepo,
            UploadInterface $upload,
            BranchInterface $branchRepo,
            AccountInterface $accountRepo
        )
    {
        $this->model           = $model;
        $this->accountHeadRepo = $accountHeadRepo;
        $this->upload          = $upload;
        $this->branchRepo      = $branchRepo;
        $this->accountRepo     = $accountRepo;

    }
    public function get(){
        return $this->model::with(['upload','user'])->where('business_id',business_id())->orderByDesc('id')->get();
    } 
    public function getFind($id){
        return $this->model::find($id);
    }
    public function store($request){
       
        try {

            $head                     = $this->accountHeadRepo->getFind($request->account_head);
            $expense                  = new Expense();
            $expense->account_head_id = $head->id; 
            $expense->business_id     = business_id();
            $expense->from_account    = $request->from_account;
            $expense->branch_id       = $request->to_branch;
            $expense->to_account      = $request->to_account;
            $expense->amount          = $request->amount;
            $expense->note            = $head->note; 
            if($request->document && !blank($request->document)):
                $expense->document_id  = $this->upload->upload('expense','',$request->document);
            endif;
            $expense->created_by   = Auth::user()->id;
            $expense->save();  

            //business balance add from branch
            $fromAccount              = $this->accountRepo->getFind($request->from_account);
            $fromAccount->balance     = ($fromAccount->balance - $request->amount);
            $fromAccount->save();

            //business expense transaction
            $fromBankTransaction              = new BankTransaction(); 
            $fromBankTransaction->expense_id  = $expense->id;
            $fromBankTransaction->user_type   = AccountType::ADMIN;
            $fromBankTransaction->business_id = business_id(); 
            $fromBankTransaction->account_id  = $request->from_account;
            $fromBankTransaction->type        = StatementType::EXPENSE;
            $fromBankTransaction->amount      = $request->amount;
            $fromBankTransaction->note        = $head->note;
            if($request->document && !blank($request->document)):
                $fromBankTransaction->document_id  = $expense->document_id;
            endif;
            $fromBankTransaction->save();


            $branch               = $this->branchRepo->getFind($request->to_branch);
            $branch->balance      = ($branch->balance + $request->amount);
            $branch->save();
 
            //branch income transaction
            $toBankTransaction              = new BankTransaction(); 
            $toBankTransaction->expense_id  = $expense->id;
            $toBankTransaction->user_type   = AccountType::BRANCH;
            $toBankTransaction->business_id = business_id();
            $toBankTransaction->branch_id   = $request->to_branch;
            $toBankTransaction->account_id  = $request->to_account;
            $toBankTransaction->type        = StatementType::INCOME;
            $toBankTransaction->amount      = $request->amount;
            $toBankTransaction->note        = $head->note;
            if($request->document && !blank($request->document)):
                $toBankTransaction->document_id  = $expense->document_id;
            endif;
            $toBankTransaction->save();
 
            $toAccount              = $this->accountRepo->getFind($request->to_account);
            $toAccount->balance     = ($toAccount->balance + $request->amount);
            $toAccount->save();

            return true;
        } catch (\Throwable $th) {
           return false;
        }
    }
    public function update($id,$request){
        try {
            $this->amountRevarse($id);
            $head                     = $this->accountHeadRepo->getFind($request->account_head);
            $expense                  = $this->getFind($id);
            $expense->account_head_id = $head->id; 
            $expense->business_id     = business_id();
            $expense->from_account    = $request->from_account;
            $expense->branch_id       = $request->to_branch;
            $expense->to_account      = $request->to_account;
            $expense->amount          = $request->amount;
            $expense->note            = $head->note; 
            if($request->document && !blank($request->document)):
                $expense->document_id  = $this->upload->upload('expense',$expense->document_id,$request->document);
            endif;
            $expense->created_by   = Auth::user()->id;
            $expense->save();  

            //business balance add from branch
            $fromAccount              = $this->accountRepo->getFind($request->from_account);
            $fromAccount->balance     = ($fromAccount->balance - $request->amount);
            $fromAccount->save();

            //business expense transaction
            $fromBankTransaction              = new BankTransaction(); 
            $fromBankTransaction->expense_id  = $expense->id;
            $fromBankTransaction->user_type   = AccountType::ADMIN;
            $fromBankTransaction->business_id = business_id(); 
            $fromBankTransaction->account_id  = $request->from_account;
            $fromBankTransaction->type        = StatementType::EXPENSE;
            $fromBankTransaction->amount      = $request->amount;
            $fromBankTransaction->note        = $head->note;
            if($request->document && !blank($request->document)):
                $fromBankTransaction->document_id  = $expense->document_id;
            endif;
            $fromBankTransaction->save();


            $branch               = $this->branchRepo->getFind($request->to_branch);
            $branch->balance      = ($branch->balance + $request->amount);
            $branch->save();
 
            //branch income transaction
            $toBankTransaction              = new BankTransaction(); 
            $toBankTransaction->expense_id  = $expense->id;
            $toBankTransaction->user_type   = AccountType::BRANCH;
            $toBankTransaction->business_id = business_id();
            $toBankTransaction->branch_id   = $request->to_branch;
            $toBankTransaction->account_id  = $request->to_account;
            $toBankTransaction->type        = StatementType::INCOME;
            $toBankTransaction->amount      = $request->amount;
            $toBankTransaction->note        = $head->note;
            if($request->document && !blank($request->document)):
                $toBankTransaction->document_id  = $expense->document_id;
            endif;
            $toBankTransaction->save();
            
            $toAccount              = $this->accountRepo->getFind($request->to_account);
            $toAccount->balance     = ($toAccount->balance + $request->amount);
            $toAccount->save();

            return true;

        } catch (\Throwable $th) {
            return false;
        }
    }

    public function amountRevarse($id){

        $expense              = $this->getFind($id); 
        $branch               = $this->branchRepo->getFind($expense->branch_id); 
        $branch->balance      = ($branch->balance - $expense->amount);
        $branch->save();
 
        $fromAccount              = $this->accountRepo->getFind($expense->from_account);
        $fromAccount->balance     = ($fromAccount->balance + $expense->amount);
        $fromAccount->save(); 

        $toAccount              = $this->accountRepo->getFind($expense->to_account);
        $toAccount->balance     = ($toAccount->balance - $expense->amount);
        $toAccount->save();
 
        BankTransaction::where('expense_id',$expense->id)->delete();
    }
 
    public function delete($id){
        try {
            $expense              = $this->getFind($id); 
            $branch               = $this->branchRepo->getFind($expense->branch_id); 
            $branch->balance      = ($branch->balance - $expense->amount);
            $branch->save();

            $toAccount              = $this->accountRepo->getFind($expense->to_account);
            $toAccount->balance     = ($toAccount->balance - $expense->amount);
            $toAccount->save();
     
            $fromAccount              = $this->accountRepo->getFind($expense->from_account);
            $fromAccount->balance     = ($fromAccount->balance + $expense->amount);
            $fromAccount->save(); 
 
            $this->upload->unlinkImage($expense->document_id);
            return $this->model::destroy($id);
        } catch (\Throwable $th) {
            return false;
        }
    }
}