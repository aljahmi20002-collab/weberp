<?php

namespace Modules\Account\Http\Controllers;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Account\Http\Requests\StoreRequest;
use Modules\Account\Repositories\AccountInterface;
use Yajra\DataTables\DataTables;

class AccountController extends Controller
{
    protected $repo;
    public function __construct(AccountInterface $repo)
    {
        $this->repo    = $repo;
    }
    public function index()
    { 
        
        return view('account::index');
    }
    public function getAllAccounts(){
        $accounts  = $this->repo->getAllAccounts();
        return DataTables::of($accounts)
        ->addIndexColumn() 
        ->editColumn('payment_gateway',function($account){
            return  __(\Config::get('pos_default.payment_gatway.'.$account->payment_gateway));
        })
        ->editColumn('account_details',function($account){
            $accountDetails = ''; 
            if($account->payment_gateway == \Modules\Account\Enums\PaymentGateway::BANK):
                    $accountDetails .= '<div class="d-flex">';
                    $accountDetails .= '<span>'. __('bank_name') .'</span>:'. @$account->bank_name;
                    $accountDetails .= '</div>';
                    $accountDetails .= '<div class="d-flex">';
                    $accountDetails .= '<span>'. __('holder_name') .'</span>:'. @$account->holder_name;
                    $accountDetails .= '</div>';
                    $accountDetails .= '<div class="d-flex">';
                    $accountDetails .= '<span>'. __('account_no') .'</span>:'. @$account->account_no;
                    $accountDetails .= '</div>';
                    $accountDetails .= '<div class="d-flex">';
                    $accountDetails .= '<span>'.__('branch_name') .'</span>: '. @$account->branch_name;
                    $accountDetails .= '</div>'; 
            elseif($account->payment_gateway == \Modules\Account\Enums\PaymentGateway::MOBILE):
                $accountDetails .= '<div class="d-flex">';
                $accountDetails .= '<span>'.__('holder_name') .'</span>:'. @$account->holder_name;
                $accountDetails .= '</div>';
                $accountDetails .= '<div class="d-flex">';
                $accountDetails .= '<span>'. __('mobile').'</span>: '. @$account->mobile;
                $accountDetails .= '</div>';
                $accountDetails .= '<div class="d-flex">';
                $accountDetails .= '<span>'.__('number_type') .'</span>:'. @$account->number_type;
                $accountDetails .= '</div>'; 
            endif;
            // if(business()):
                $accountDetails .= '<div class="d-flex">';
                $accountDetails .= '<span>'. __('balance') .'</span>:'.  businessCurrency(business_id()).' '. @$account->balance;
                $accountDetails .= '</div>';  
            // endif;

            return $accountDetails;
        })
        ->editColumn('status',function($account){
            return @$account->my_status;
        })
        ->editColumn('make_default',function($account){
            $default ='';
            if($account->my_default):
                $default .= '<span class="badge badge-pill badge-success">'. __('default').'</span>';
            else:
                $default .= '<form action="'.route('accounts.account.make.default',@$account->id) .'" method="post" class="delete-form" id="delete" data-yes='. __('yes') .' data-cancel="'. __('cancel') .'" data-title="'. __('add_default_account_message') .'">';
                $default .=method_field('PUT');
                $default .=csrf_field();
                $default .= '<button type="submit" class="badge  badge-pill badge-primary"><i class="fa fa-plus text-white"> </i> '. __('default') .' </button>';
                $default .='</form>'; 
            endif;
            return $default;
        }) 
        ->editColumn('action',function($account){
            $action = '';
            if($account->my_default):
                return '...';
            endif;
            if(hasPermission('account_update') || hasPermission('account_delete') || hasPermission('account_status_update')):
                $action .= '<div class="dropdown">';
                $action .= '<a href="#" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                $action .= '<i class="fa fa-cogs"></i>';
                $action .= '</a>';
                $action .= '<div class="dropdown-menu todo-dropdown-menu" aria-labelledby="dropdownMenuButton">';
                    if(hasPermission('account_status_update')):
                        $action .= '<a class="dropdown-item" href="'. route('accounts.account.status.update',$account->id) .'">';
                        $action .= $account->status == \App\Enums\Status::ACTIVE? '<i class="fa fa-ban"></i>':'<i class="fa fa-check"></i>';
                        $action .= @statusUpdate($account->status);
                        $action .= '</a>';
                    endif;
                    
                    if(hasPermission('account_update')):
                        $action .= '<a href="'.route('accounts.account.edit',$account->id) .'" class="dropdown-item"  ><i class="fas fa-pen"></i>'. __('edit') .'</a>';
                    endif;
                    if(hasPermission('account_delete')):
                        $action .= '<form id="delete" action="'. route('accounts.account.delete',@$account->id).'" method="post" class="delete-form"  data-yes='. __('yes') .' data-cancel="'. __('cancel') .'" data-title="'. __('delete_account') .'">';
                        $action .= method_field('delete');
                        $action .= csrf_field();
                        $action .= '<button type="submit" class="dropdown-item"  >';
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
        ->rawColumns(['payment_gateway','account_details','status','make_default','action'])
        ->make(true);
    }
 

    public function create()
    {
        return view('account::create');
    }
    public function store(StoreRequest $request)
    {
         
        if(env('DEMO')){
            Toastr::error(__('store_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }
        if($this->repo->store($request)){
            Toastr::success(__('account_added_successfully'),__('success'));
            return redirect()->route('accounts.account.index');
        }else{
            Toastr::error(__('error'),__('errors'));
            return redirect()->back();
        }
    }

    public function makeDefault($id){
        if(env('DEMO')){
            Toastr::error(__('store_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }
        if($this->repo->makeDefault($id)){
            Toastr::success(__('account_update_successfully'),__('success'));
            return redirect()->route('accounts.account.index');
        }else{
            Toastr::error(__('error'),__('errors'));
            return redirect()->back();
        }
    }
    public function edit($id)
    {
        $account = $this->repo->getFind($id);
        return view('account::edit',compact('account'));
    } 
    public function update(StoreRequest $request)
    {
        if(env('DEMO')){
            Toastr::error(__('update_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }
        if($this->repo->update($request->id,$request)){
            Toastr::success(__('account_update_successfully'),__('success'));
            return redirect()->route('accounts.account.index');
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
            Toastr::success(__('account_deleted_successfully'),__('success'));
            return redirect()->back();
        }else{
            Toastr::error(__('error'),__('errors'));
            return redirect()->back();
        }
    } 
    public function statusUpdate($id){
        if(env('DEMO')){
            Toastr::error(__('update_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }
        if($this->repo->statusUpdate($id)){
            Toastr::success(__('account_status_update_successfully'),__('success'));
            return redirect()->back();
        }else{
            Toastr::error(__('error'),__('errors'));
            return redirect()->back();
        }
    }

    public function bankTransaction(){
        return view('account::bank_transaction_list');
    }
    
    public function getbankTransaction(){
        $bank_transactions    = $this->repo->getBankTransactions();
        return DataTables::of($bank_transactions)
        ->addIndexColumn() 
        ->editColumn('account_details',function($transaction){
            $accountDetails  = ''; 
            $accountDetails .=' <div class="d-flex">';
            $accountDetails .= '<span>'. __('payment_gateway').'</span>:'. __(\Config::get('pos_default.payment_gatway.'.$transaction->account->payment_gateway));
            $accountDetails .= '</div> ';
            if($transaction->account->payment_gateway == \Modules\Account\Enums\PaymentGateway::BANK):
                $accountDetails .=  '<div class="d-flex">';
                $accountDetails .=  '<span>'. __('bank_name') .'</span>:'. @$transaction->account->bank_name;
                $accountDetails .=  '</div>';
                $accountDetails .= '<div class="d-flex">';
                $accountDetails .= '<span>'. __('holder_name') .'</span>:'. @$transaction->account->holder_name;
                $accountDetails .=  '</div>';
                $accountDetails .= '<div class="d-flex">';
                $accountDetails .= '<span>'.__('account_no') .'</span>:'. @$transaction->account->account_no;
                $accountDetails .=  '</div>';
                $accountDetails .= ' <div class="d-flex">';
                $accountDetails .= '<span>'. __('branch_name') .'</span>:'. @$transaction->account->branch_name;
                $accountDetails .= '</div>';
            elseif($transaction->account->payment_gateway == \Modules\Account\Enums\PaymentGateway::MOBILE):
                $accountDetails .= '<div class="d-flex">';
                $accountDetails .= '<span>'. __('holder_name') .'</span>:'.@$transaction->account->holder_name;
                $accountDetails .= '</div>';
                $accountDetails .=  '<div class="d-flex">';
                $accountDetails .= '<span>'. __('mobile') .'</span>: '.@$transaction->account->mobile;
                $accountDetails .=  '</div>';
                $accountDetails .= ' <div class="d-flex">';
                $accountDetails .= '<span>'. __('number_type') .'</span>: '. @$transaction->account->number_type;
                $accountDetails .= '</div>'; 
            endif;
            return $accountDetails;
        })
        ->editColumn('type',function($transaction){
            return $transaction->my_type;
        })
        ->editColumn('amount',function($transaction){
            return  businessCurrency(business_id()) .' '.$transaction->amount;
        })
        ->editColumn('note',function($transaction){
            return @__($transaction->note);
        })
        ->editColumn('document',function($transaction){
            return '<a href="'.@$transaction->document_file.'" download="">'. __('download') .'</a>';
        })
        ->editColumn('date',function($transaction){
            return \Carbon\Carbon::parse($transaction->created_at)->format('Y-m-d h:i A');
        })
        ->rawColumns(['account_details','type','amount','note','document','date'])
        ->make(true);
    }
}
