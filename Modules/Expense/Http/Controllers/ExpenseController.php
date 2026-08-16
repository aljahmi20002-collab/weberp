<?php

namespace Modules\Expense\Http\Controllers;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Account\Repositories\AccountInterface;
use Modules\AccountHead\Repositories\AccountHeadInterface;
use Modules\Branch\Repositories\BranchInterface;
use Modules\Expense\Http\Requests\StoreRequest;
use Modules\Expense\Repositories\ExpenseInterface;
use Yajra\DataTables\DataTables;

class ExpenseController extends Controller
{
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
       
        return view('expense::index' );
    }

    public function getAllExpense(){
        $expenses    = $this->repo->get();
        return DataTables::of($expenses)
        ->addIndexColumn() 
        ->editColumn('from_account',function($expense){
            $fromAccountDetails = '';
            $fromAccountDetails .= '<div class="d-flex">';
            $fromAccountDetails .= '<span>'. __('payment_gateway') .'</span>:'.  __(\Config::get('pos_default.payment_gatway.'.$expense->fromAccount->payment_gateway));
            $fromAccountDetails .='</div>';
            if($expense->fromAccount->payment_gateway == \Modules\Account\Enums\PaymentGateway::BANK):
                $fromAccountDetails .= '<div class="d-flex">';
                $fromAccountDetails .= '<span>'.__('bank_name') .'</span>:'. @$expense->fromAccount->bank_name;
                $fromAccountDetails .= '</div>';
                $fromAccountDetails .= '<div class="d-flex">';
                $fromAccountDetails .= '<span>'. __('holder_name') .'</span>:'. @$expense->fromAccount->holder_name;
                $fromAccountDetails .= '</div>';
                $fromAccountDetails .= '<div class="d-flex">';
                $fromAccountDetails .= '<span>'.__('account_no') .'</span>:'. @$expense->fromAccount->account_no;
                $fromAccountDetails .= '</div>';
                $fromAccountDetails .= '<div class="d-flex">';
                $fromAccountDetails .= '<span>'. __('branch_name') .'</span>:'.@$expense->fromAccount->branch_name;
                $fromAccountDetails .= '</div>';
            elseif($expense->fromAccount->payment_gateway == \Modules\Account\Enums\PaymentGateway::MOBILE):
                $fromAccountDetails .= '<div class="d-flex">';
                $fromAccountDetails .= '<span>'.__('holder_name') .'</span>:'.@$expense->fromAccount->holder_name;
                $fromAccountDetails .= '</div>';
                $fromAccountDetails .= '<div class="d-flex">';
                $fromAccountDetails .= '<span>'. __('mobile').'</span>:'. @$expense->fromAccount->mobile;
                $fromAccountDetails .= '</div>';
                $fromAccountDetails .= '<div class="d-flex">';
                $fromAccountDetails .= '<span>'.__('number_type') .'</span>:'. @$expense->fromAccount->number_type;
                $fromAccountDetails .= '</div>';
            endif;
            return $fromAccountDetails; 
        })
        ->editColumn('to_branch',function($expense){
            return @$expense->branch->name;
        })
        ->editColumn('to_account',function($expense){
            $toAccountDetails = '';
            $toAccountDetails .= '<div class="d-flex">';
            $toAccountDetails .= '<span>'. __('payment_gateway') .'</span>: '.  __(\Config::get('pos_default.payment_gatway.'.$expense->toAccount->payment_gateway));
            $toAccountDetails .= '</div>';
            if($expense->toAccount->payment_gateway == \Modules\Account\Enums\PaymentGateway::BANK):
                $toAccountDetails .= '<div class="d-flex">';
                $toAccountDetails .= '<span>'. __('bank_name') .'</span>:'. @$expense->toAccount->bank_name;
                $toAccountDetails .= '</div>';
                $toAccountDetails .= '<div class="d-flex">';
                $toAccountDetails .= '<span>'.__('holder_name').'</span>:'. @$expense->toAccount->holder_name;
                $toAccountDetails .= '</div>';
                $toAccountDetails .= '<div class="d-flex">';
                $toAccountDetails .= '<span>'.__('account_no') .'</span>: '. @$expense->toAccount->account_no;
                $toAccountDetails .= '</div>';
                $toAccountDetails .= '<div class="d-flex">';
                $toAccountDetails .= '<span>'. __('branch_name') .'</span>:'. @$expense->toAccount->branch_name;
                $toAccountDetails .= '</div>'; 
            elseif($expense->toAccount->payment_gateway == \Modules\Account\Enums\PaymentGateway::MOBILE):
                $toAccountDetails .= '<div class="d-flex">';
                $toAccountDetails .= '<span>'. __('holder_name') .'</span>:'.@$expense->toAccount->holder_name ;
                $toAccountDetails .= '</div>';
                $toAccountDetails .= '<div class="d-flex">';
                $toAccountDetails .= '<span>'. __('mobile').'</span>:'. @$expense->toAccount->mobile;
                $toAccountDetails .= '</div>';
                $toAccountDetails .= '<div class="d-flex">';
                $toAccountDetails .= '<span>'. __('number_type').'</span>:'.@$expense->toAccount->number_type;
                $toAccountDetails .= '</div>';
            endif;

            return $toAccountDetails;
        })
        ->editColumn('amount',function($expense){
            return businessCurrency(business_id()) .' '.@$expense->amount;
        })
        ->editColumn('note',function($expense){
            return @$expense->note;
        })
        ->editColumn('document',function($expense){
            return  '<a href="'. @$expense->document_file .'" download="">'. __('download') .'</a>';
        })
        ->editColumn('created_by',function($expense){
            return @$expense->user->name;
        })
        ->editColumn('action',function($expense){
            $action = '';
            if(hasPermission('expense_update') || hasPermission('expense_delete')):
                $action .= '<div class="dropdown">';
                $action .= '<a href="#" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                $action .= '<i class="fa fa-cogs"></i>';
                $action .= '</a>';
                $action .= '<div class="dropdown-menu todo-dropdown-menu" aria-labelledby="dropdownMenuButton">';
                    if(hasPermission('expense_update')):
                        $action .= '<a href="'.route('accounts.expense.edit',$expense->id) .'" class="dropdown-item"  ><i class="fas fa-pen"></i>'.__('edit') .'</a>';
                    endif;
                    if(hasPermission('expense_delete')):
                        $action .= '<form action="'. route('accounts.expense.delete',$expense->id) .'" method="post" class="delete-form" id="delete" data-yes='. __('yes') .' data-cancel="'. __('cancel') .'" data-title="'. __('delete_expense') .'">';
                        $action .= method_field('delete');
                        $action .= csrf_field();
                        $action .= '<button type="submit" class="dropdown-item "  >';
                        $action .= '<i class="fas fa-trash-alt"></i>'.__('delete');
                        $action .= '</button>';
                        $action .= '</form>';
                    endif; 
                    $action .= '</div>';
                    $action .= '</div>';
            else:
                return '<i class="fa fa-ellipsis"></i>';
            endif;
            return $action;
        })
        ->rawColumns(['from_account','to_branch','to_account','amount','note','document','created_by','action'])
        ->make(true);
    }


    public function create()
    {
        $accountHeads     = $this->accountHeadRepo->getExpenseActiveHead();
        $businessAccounts = $this->accountRepo->getBusinessActiveAccounts();
        $branches         = $this->branchRepo->getAll(business_id());

        return view('expense::create',compact('accountHeads','businessAccounts','branches'));
    }
 
    public function store(StoreRequest $request)
    {
        if(env('DEMO')){
            Toastr::error(__('store_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        } 
        if($this->repo->store($request)){
            Toastr::success(__('expense_added_successfully'),__('success'));
            return redirect()->route('accounts.expense.index');
        }else{
            Toastr::error(__('error'),__('errors'));
            return redirect()->back();
        }
    } 
    public function edit($id)
    {
        $expense          = $this->repo->getFind($id);
        $accountHeads     = $this->accountHeadRepo->getExpenseActiveHead();
        $businessAccounts = $this->accountRepo->getBusinessActiveAccounts();
        $branches         = $this->branchRepo->getAll(business_id());
        $branchAccounts = $this->accountRepo->getBranchAccounts($expense->branch_id);
        return view('expense::edit',compact('accountHeads','businessAccounts','branches','expense','branchAccounts'));
    }

    public function update(StoreRequest $request)
    {
        if(env('DEMO')){
            Toastr::error(__('update_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        } 
        if($this->repo->update($request->id,$request)){
            Toastr::success(__('expense_updated_successfully'),__('success'));
            return redirect()->route('accounts.expense.index');
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
            Toastr::success(__('expense_deleted_successfully'),__('success'));
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
