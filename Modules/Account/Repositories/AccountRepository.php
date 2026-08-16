<?php

namespace Modules\Account\Repositories;

use App\Enums\Status;
use App\Enums\UserType;
use Illuminate\Support\Facades\Auth;
use Modules\Account\Entities\Account;
use Modules\Account\Entities\BankTransaction;
use Modules\Account\Enums\AccountType;
use Modules\Account\Enums\PaymentGateway;
use Modules\Plan\Enums\IsDefault;

class AccountRepository implements AccountInterface
{

    protected $model;
    public function __construct(Account $model)
    {
        $this->model   = $model;
    }
    public function get()
    {
        return Account::where('business_id', business_id())->where(function ($query) {
            if (business()) :
                $query->where('account_type', AccountType::ADMIN);
            elseif (isUser()) :
                $query->where('account_type', AccountType::BRANCH);
                $query->where('branch_id', Auth::user()->branch_id);
            endif;
        })->orderByDesc('id')->paginate(10);
    }
    public function getAllAccounts()
    {
        return Account::where('business_id', business_id())->where(function ($query) {
            if (business()) :
                $query->where('account_type', AccountType::ADMIN);
            elseif (isUser()) :
                $query->where('account_type', AccountType::BRANCH);
                $query->where('branch_id', Auth::user()->branch_id);
            endif;
        })->orderByDesc('id')->get();
    }
    public function getBranchAccounts($branch_id)
    {
        return Account::where('business_id', business_id())->where(function ($query) use ($branch_id) {
            $query->where('account_type', AccountType::BRANCH);
            $query->where('branch_id', $branch_id);
        })->where('status', Status::ACTIVE)->orderByDesc('id')->get();
    }

    public function getBusinessActiveAccounts()
    {
        return Account::where('business_id', business_id())->where('account_type', AccountType::ADMIN)->where('status', Status::ACTIVE)->orderByDesc('id')->get();
    }
    public function getAdminActiveAccount()
    {
        return $this->getBusinessActiveAccounts();
    }

    public function getFind($id)
    {
        return $this->model::find($id);
    }

    public function defaultAccountStore($business_id)
    {
        try {
            $account                  = new $this->model();
            $account->business_id     = $business_id;
            $account->account_type    = AccountType::ADMIN;
            $account->payment_gateway = PaymentGateway::CASH;
            $account->is_default      = IsDefault::YES;
            $account->save();
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    public function makeDefault($id)
    {
        try {
            $account_type = AccountType::ADMIN;
            if (isUser()) :
                $account_type = AccountType::BRANCH;
            endif;
            $accounts  = $this->model::where(['business_id' => business_id(), 'account_type' => $account_type]);
            if (isUser()) :
                $accounts  = $accounts->where('branch_id', Auth::user()->branch_id);
            endif;
            $accounts  = $accounts->get();
            foreach ($accounts as $key => $acc) {
                // make default 
                $acc->is_default = IsDefault::NO;
                $acc->save();
            }
            // make default
            $account             = $this->model::find($id);
            $account->is_default = IsDefault::YES;
            $account->save();
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    public function store($request)
    {
        try {
            
            $account                  = new $this->model();
            $account->business_id     = business_id();
            $account_type = AccountType::ADMIN;
            if (isUser()) :
                $account->branch_id       = Auth::user()->branch_id;
                $account_type             = AccountType::BRANCH;
            endif;
            $account->account_type    = $account_type;
            $account->payment_gateway = $request->payment_gateway;
            if (business() && !blank($request->balance)) :
                $account->balance         = $request->balance;
            endif;
            if ($request->payment_gateway == PaymentGateway::BANK) :
                $this->bankInfo($account, $request);
            elseif ($request->payment_gateway == PaymentGateway::MOBILE) :
                $this->mobileInfo($account, $request);
            endif;
            $account->status          = $request->status == 'on' ? Status::ACTIVE : Status::INACTIVE;
            $account->save();
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    public function bankInfo($account, $request)
    {
        $account->holder_name  = $request->holder_name;
        $account->bank_name    = $request->bank_name;
        $account->account_no   = $request->account_no;
        $account->branch_name  = $request->branch_name;
        //mobile info blank 
        $account->mobile        = null;
        $account->number_type   = null;
    }
    public function mobileInfo($account, $request)
    {
        $account->holder_name   = $request->holder_name;
        $account->mobile        = $request->mobile;
        $account->number_type   = $request->number_type;
        //bank info blank
        $account->bank_name    = null;
        $account->account_no   = null;
        $account->branch_name  = null;
    }

    public function update($id, $request)
    {
        try {

            $account                  = $this->getFind($id);
            $account->business_id     = business_id();
            $account_type = AccountType::ADMIN;
            if (isUser()) :
                $account->branch_id       = Auth::user()->branch_id;
                $account_type             = AccountType::BRANCH;
            endif;
            $account->account_type    = $account_type;
            $account->payment_gateway = $request->payment_gateway;
            if (business() && !blank($request->balance)) :
                $account->balance         = $request->balance;
            endif;
            if ($request->payment_gateway == PaymentGateway::BANK) :
                $this->bankInfo($account, $request);
            elseif ($request->payment_gateway == PaymentGateway::MOBILE) :
                $this->mobileInfo($account, $request);
            endif;
            $account->status          = $request->status == 'on' ? Status::ACTIVE : Status::INACTIVE;
            $account->save();

            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
    public function delete($id)
    {
        return $this->model::destroy($id);
    }
    public function statusUpdate($id)
    {
        try {
            $account           = $this->getFind($id);
            $account->status   = $account->status == Status::ACTIVE ? Status::INACTIVE : Status::ACTIVE;
            $account->save();
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    public function getBankTransactions()
    {
        return BankTransaction::where(function ($query) {
            $query->where('business_id', business_id());
            if (business()) :
                $query->where('user_type', AccountType::ADMIN);
            else :
                $query->where('user_type', AccountType::BRANCH);
                $query->where('branch_id', Auth::user()->branch_id);
            endif;
        })->orderByDesc('id')->get();
    }
}
