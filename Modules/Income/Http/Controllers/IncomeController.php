<?php

namespace Modules\Income\Http\Controllers;

use App\Enums\AccountHead;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;
use Modules\Account\Repositories\AccountInterface;
use Modules\AccountHead\Repositories\AccountHeadInterface;
use Modules\Branch\Repositories\BranchInterface;
use Modules\Income\Http\Requests\StoreRequest;
use Modules\Income\Repositories\IncomeInterface;
use Yajra\DataTables\DataTables;

class IncomeController extends Controller
{

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
      
        return view('income::index');
    }

    public function getAllIncome(){
        $incomes   = $this->repo->get();
        return DataTables::of($incomes)
        ->addIndexColumn() 
        ->editColumn('branch',function($income){
            return @$income->branch->name;
        })
        ->editColumn('from_account',function($income){
            $fromAccountDetails = '';
            $fromAccountDetails .= '<div class="d-flex">';
            $fromAccountDetails .= '<span>'. __('payment_gateway') .'</span>:'.  __(Config::get('pos_default.payment_gatway.'.$income->fromAccount->payment_gateway));
            $fromAccountDetails .=' </div>';
            if($income->fromAccount->payment_gateway == \Modules\Account\Enums\PaymentGateway::BANK):
                $fromAccountDetails .= '<div class="d-flex">';
                $fromAccountDetails .= '<span>'. __('bank_name') .'</span>:'.  @$income->fromAccount->bank_name;
                $fromAccountDetails .= '</div>';
                $fromAccountDetails .= '<div class="d-flex">';
                $fromAccountDetails .= '<span>'. __('holder_name') .'</span>: '. @$income->fromAccount->holder_name;
                $fromAccountDetails .= '</div>';
                $fromAccountDetails .= '<div class="d-flex">';
                $fromAccountDetails .= '<span>'. __('account_no') .'</span>:'. @$income->fromAccount->account_no;
                $fromAccountDetails .= '</div>';
                $fromAccountDetails .= '<div class="d-flex">';
                $fromAccountDetails .= '<span>'.__('branch_name') .'</span>:'. @$income->fromAccount->branch_name;
                $fromAccountDetails .= '</div>'; 
            elseif($income->fromAccount->payment_gateway == \Modules\Account\Enums\PaymentGateway::MOBILE):
                $fromAccountDetails .= '<div class="d-flex">';
                $fromAccountDetails .= '<span>'. __('holder_name') .'</span>:'. @$income->fromAccount->holder_name;
                $fromAccountDetails .= '</div>';
                $fromAccountDetails .= '<div class="d-flex">';
                $fromAccountDetails .= '<span>'. __('mobile') .'</span>:'. @$income->fromAccount->mobile;
                $fromAccountDetails .= '</div>';
                $fromAccountDetails .= '<div class="d-flex">';
                $fromAccountDetails .= '<span>'. __('number_type') .'</span>:'. @$income->fromAccount->number_type;
                $fromAccountDetails .= '</div> ';
            endif;
            return $fromAccountDetails;
        })
        ->editColumn('to_account',function($income){
            $toAccountDetails = '';
            $toAccountDetails .= '<div class="d-flex">';
            $toAccountDetails .= '<span>'. __('payment_gateway') .'</span>:'. __(Config::get('pos_default.payment_gatway.'.$income->toAccount->payment_gateway));
            $toAccountDetails .= '</div>';
            if($income->toAccount->payment_gateway == \Modules\Account\Enums\PaymentGateway::BANK):
            $toAccountDetails .= '<div class="d-flex">';
            $toAccountDetails .= '<span>'.__('bank_name') .'</span>:'. @$income->toAccount->bank_name;
            $toAccountDetails .=  '</div>';
            $toAccountDetails .=  '<div class="d-flex">';
            $toAccountDetails .=  '<span>'. __('holder_name') .'</span>:'.  @$income->toAccount->holder_name;
            $toAccountDetails .=  '</div>';
            $toAccountDetails .=  '<div class="d-flex">';
            $toAccountDetails .=  '<span>'. __('account_no') .'</span>:'.@$income->toAccount->account_no;
            $toAccountDetails .=  '</div>';
            $toAccountDetails .=  '<div class="d-flex">';
            $toAccountDetails .=  '<span>'. __('branch_name') .'</span>: '.@$income->toAccount->branch_name;
            $toAccountDetails .=   '</div>';
            elseif($income->toAccount->payment_gateway == \Modules\Account\Enums\PaymentGateway::MOBILE):
                $toAccountDetails .= '<div class="d-flex">';
                $toAccountDetails .= '<span>'.__('holder_name') .'</span>:'. @$income->toAccount->holder_name;
                $toAccountDetails .=  '</div>';
                $toAccountDetails .= '<div class="d-flex">';
                $toAccountDetails .= '<span>'. __('mobile') .'</span>: '.@$income->toAccount->mobile;
                $toAccountDetails .=  '</div>';
                $toAccountDetails .=  '<div class="d-flex">';
                $toAccountDetails .=  '<span>'. __('number_type') .'</span>:'. @$income->toAccount->number_type;
                $toAccountDetails .=  '</div>'; 
            endif;  
            return $toAccountDetails;

        })
        ->editColumn('amount',function($income){
            return businessCurrency(business_id()) .' '. @$income->amount;
        })
        ->editColumn('note',function($income){
            return  @$income->note;
        })
        ->editColumn('document',function($income){
            return '<a href="'. @$income->document_file .'" download="">'. __('download') .'</a>';
        })
        ->editColumn('created_by',function($income){
            return @$income->user->name;
        })
        ->editColumn('action',function($income){
            $action = '';
            if(hasPermission('income_update') || hasPermission('income_delete')): 
                $action .= '<div class="dropdown">';
                $action .= '<a href="#" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                $action .= '<i class="fa fa-cogs"></i>';
                $action .= '</a>';
                $action .= '<div class="dropdown-menu todo-dropdown-menu" aria-labelledby="dropdownMenuButton">';
                    if(hasPermission('income_update')):
                        $action .= '<a href="'. route('accounts.income.edit',$income->id) .'" class="dropdown-item"  ><i class="fas fa-pen"></i>'. __('edit') .'</a>';
                    endif;
                    if(hasPermission('income_delete')):
                        $action .= '<form action="'. route('accounts.income.delete',$income->id) .'" method="post" class="delete-form" id="delete" data-yes='.__('yes') .' data-cancel="'.__('cancel') .'" data-title="'.__('delete_income') .'">';
                        $action .= method_field('delete');
                        $action .= csrf_field();
                        $action .= '<button type="submit" class="dropdown-item "  >';
                        $action .= '<i class="fas fa-trash-alt"></i>'. __('delete');
                        $action .= '</button>';
                        $action .= '</form>';
                    endif;
                    $action .= '</div>';
                    $action .= '</div>';
            else:
                return '...';
            endif;
            return $action;
        }) 
        ->rawColumns(['branch','from_account','to_account','amount','note','document','created_by','action'])
        ->make(true);
    }
 
    public function create()
    {
        $accountHeads   = $this->accountHeadRepo->getIncomeActiveHead();
        $accounts       = $this->accountRepo->getBusinessActiveAccounts();
        $branches       = $this->branchRepo->getAll(business_id());
        return view('income::create',compact('accountHeads','accounts','branches'));
    }

    public function store(StoreRequest $request)
    {
        if(env('DEMO')){
            Toastr::error(__('store_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        } 
        if($this->repo->store($request)){
            Toastr::success(__('income_added_successfully'),__('success'));
            return redirect()->route('accounts.income.index');
        }else{
            Toastr::error(__('error'),__('errors'));
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        $accountHeads   = $this->accountHeadRepo->getIncomeActiveHead();
        $accounts       = $this->accountRepo->getBusinessActiveAccounts();
        $branches       = $this->branchRepo->getAll(business_id());
        $income         = $this->repo->getFind($id);
        $branchAccounts = $this->accountRepo->getBranchAccounts($income->branch_id);
        return view('income::edit',compact('income', 'accounts', 'branches','accountHeads','branchAccounts'));
    }
 
    public function update(StoreRequest $request)
    {
        if(env('DEMO')){
            Toastr::error(__('update_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        } 
        if($this->repo->update($request->id,$request)){
            Toastr::success(__('income_update_successfully'),__('success'));
            return redirect()->route('accounts.income.index');
        }else{
            Toastr::error(__('error'),__('errors'));
            return redirect()->back();
        }
    }
 
    public function destroy($id)
    {
        if(env('DEMO')){
            Toastr::error(__('delete_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        } 
        if($this->repo->delete($id)){
            Toastr::success(__('income_deleted_successfully'),__('success'));
            return redirect()->back();
        }else{
            Toastr::error(__('error'),__('errors'));
            return redirect()->back();
        }
    }
 
    public function branchAccount(Request $request){
        if($request->ajax()):
            $accounts = $this->accountRepo->getBranchAccounts($request->branch_id);
            return view('income::form_inputs.account_options',compact('accounts'));
        endif;
        return null;
    }
}
