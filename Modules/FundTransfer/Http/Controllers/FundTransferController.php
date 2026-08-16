<?php

namespace Modules\FundTransfer\Http\Controllers;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;
use Modules\Account\Repositories\AccountInterface;
use Modules\FundTransfer\Http\Requests\StoreRequest;
use Modules\FundTransfer\Repositories\FundTransferInterface;
use Yajra\DataTables\DataTables;

class FundTransferController extends Controller
{
    protected $repo,$accountRepo;
    public function __construct(FundTransferInterface $repo,AccountInterface $accountRepo)
    {
        $this->repo        = $repo;
        $this->accountRepo = $accountRepo;
    }
 
    public function index()
    {
       
        return view('fundtransfer::index');
    }
 
    public function getAllfundTransfer(){
        $fund_transfers    = $this->repo->get();
        return DataTables::of($fund_transfers)
        ->addIndexColumn() 
        ->editColumn('from_account',function($fund_transfer){
            $fromAccountDetails = '';
            $fromAccountDetails .= '<div class="d-flex">';
            $fromAccountDetails .= '<span>'. __('payment_gateway') .'</span>: '.  __(Config::get('pos_default.payment_gatway.'.$fund_transfer->fromAccount->payment_gateway));
            $fromAccountDetails .='</div>';
            if($fund_transfer->fromAccount->payment_gateway == \Modules\Account\Enums\PaymentGateway::BANK):
                $fromAccountDetails .= '<div class="d-flex">';
                $fromAccountDetails .= '<span>'.__('bank_name').'</span>: '. @$fund_transfer->fromAccount->bank_name .'
                </div>';
                $fromAccountDetails .= '<div class="d-flex">';
                $fromAccountDetails .= '<span>'. __('holder_name') .'</span>:'.  @$fund_transfer->fromAccount->holder_name;
                $fromAccountDetails .= '</div>';
                $fromAccountDetails .= '<div class="d-flex">';
                $fromAccountDetails .= '<span>'. __('account_no') .'</span>:'. @$fund_transfer->fromAccount->account_no;
                $fromAccountDetails .= '</div>';
                $fromAccountDetails .= '<div class="d-flex">';
                $fromAccountDetails .= '<span>'. __('branch_name') .'</span>:'. @$fund_transfer->fromAccount->branch_name;
                $fromAccountDetails .= '</div>'; 
            elseif($fund_transfer->fromAccount->payment_gateway == \Modules\Account\Enums\PaymentGateway::MOBILE):
                $fromAccountDetails .= '<div class="d-flex">';
                $fromAccountDetails .= '<span>'. __('holder_name') .'</span>:'. @$fund_transfer->fromAccount->holder_name;
                $fromAccountDetails .= '</div>';
                $fromAccountDetails .= '<div class="d-flex">';
                $fromAccountDetails .= '<span>'. __('mobile') .'</span>:'.@$fund_transfer->fromAccount->mobile;
                $fromAccountDetails .= '</div>';
                $fromAccountDetails .= '<div class="d-flex">';
                $fromAccountDetails .= '<span>'.__('number_type').'</span>: '. @$fund_transfer->fromAccount->number_type;
                $fromAccountDetails .= '</div>'; 
            endif;

            return $fromAccountDetails;
        })
        ->editColumn('to_account',function($fund_transfer){
            $toAccountDetails = '';
            $toAccountDetails .= '<div class="d-flex">';
            $toAccountDetails .= '<span>'. __('payment_gateway') .'</span>:'. __(Config::get('pos_default.payment_gatway.'.$fund_transfer->toAccount->payment_gateway));
            $toAccountDetails .= '</div>';
            if($fund_transfer->toAccount->payment_gateway == \Modules\Account\Enums\PaymentGateway::BANK):
                $toAccountDetails .= '<div class="d-flex">';
                $toAccountDetails .= '<span>'. __('bank_name') .'</span>:'.  @$fund_transfer->toAccount->bank_name;
                $toAccountDetails .= '</div>';
                $toAccountDetails .= '<div class="d-flex">';
                $toAccountDetails .= ' <span>'.__('holder_name') .'</span>:'. @$fund_transfer->toAccount->holder_name;
                $toAccountDetails .= '</div>';
                $toAccountDetails .= ' <div class="d-flex">';
                $toAccountDetails .= '<span>'.__('account_no') .'</span>:'. @$fund_transfer->toAccount->account_no;
                $toAccountDetails .= '</div>';
                $toAccountDetails .= '<div class="d-flex">';
                $toAccountDetails .= '<span>'. __('branch_name') .'</span>:'. @$fund_transfer->toAccount->branch_name;
                $toAccountDetails .= '</div> ';
            elseif($fund_transfer->toAccount->payment_gateway == \Modules\Account\Enums\PaymentGateway::MOBILE):
                $toAccountDetails .= '<div class="d-flex">';
                $toAccountDetails .= '<span>'.__('holder_name') .'</span>: '. @$fund_transfer->toAccount->holder_name;
                $toAccountDetails .= '</div>';
                $toAccountDetails .= '<div class="d-flex">';
                $toAccountDetails .= '<span>'.__('mobile') .'</span>:'. @$fund_transfer->toAccount->mobile;
                $toAccountDetails .= '</div>';
                $toAccountDetails .= '<div class="d-flex">';
                $toAccountDetails .= '<span>'. __('number_type').'</span>:'. @$fund_transfer->toAccount->number_type;
                $toAccountDetails .= '</div>'; 
            endif; 

            return $toAccountDetails;
        })
        ->editColumn('amount',function($fund_transfer){
            return businessCurrency(business_id()) .' '.@$fund_transfer->amount;
        })
        ->editColumn('description',function($fund_transfer){
            return  @$fund_transfer->description;
        })
        ->editColumn('action',function($fund_transfer){

            if(hasPermission('fund_transfer_update') || hasPermission('fund_transfer_delete')): 
                $action   = '';
                $action  .=  '<div class="dropdown">';
                $action  .= '<a href="#" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                $action  .= '<i class="fa fa-cogs"></i>';
                $action  .=  '</a>';
                $action  .= '<div class="dropdown-menu todo-dropdown-menu" aria-labelledby="dropdownMenuButton">';
                    if(hasPermission('fund_transfer_update')):
                        $action  .= '<a href="'. route('accounts.fund.transfer.edit',$fund_transfer->id) .'" class="dropdown-item" ><i class="fas fa-pen"></i>'. __('edit') .'</a>';
                    endif;
                    if(hasPermission('fund_transfer_delete')):
                        $action  .= '<form id="delete" action="'. route('accounts.fund.transfer.delete',@$fund_transfer->id) .'" method="post" class="delete-form"  data-yes='. __('yes') .' data-cancel="'. __('cancel') .'" data-title="'.__('delete_fund_transfer') .'">';
                        $action  .=  method_field('delete');
                        $action  .= csrf_field();
                        $action  .= '<button type="submit" class="dropdown-item"  >';
                        $action  .=  '<i class="fas fa-trash-alt"></i>'.__('delete');
                        $action  .=  '</button>';
                        $action  .=  '</form>';
                    endif;
                $action  .= '</div>';
                $action  .= '</div>';  
            else:
                return '...';
            endif;

            return $action;

        }) 
        ->rawColumns(['from_account','to_account','amount','description','action'])
        ->make(true);
    }


    public function create()
    {
        $accounts    = $this->accountRepo->getAdminActiveAccount();
        return view('fundtransfer::create',compact('accounts'));
    }

 
    public function store(StoreRequest $request)
    {
        if(env('DEMO')){
            Toastr::error(__('store_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }
        $account   = $this->accountRepo->getFind($request->from_account);
        if($account->balance < $request->amount):
            Toastr::warning(__('not_enough_balance'),'warning');
            return redirect()->back()->withInput();
        endif;

        if($request->amount <= 0):
            Toastr::warning(__('more_than_0_amount'),__('warning'));
            return redirect()->back()->withInput();
        endif;

        if($this->repo->store($request)){
            Toastr::success(__('fund_transfered_successfully'),__('success'));
            return redirect()->route('accounts.fund.transfer.index');
        }else{
            Toastr::error(__('error'),__('errors'));
            return redirect()->back();
        }
    }
 
 
    public function edit($id)
    {
        $accounts       = $this->accountRepo->getAdminActiveAccount();
        $fund_transfer  = $this->repo->getFind($id);
        return view('fundtransfer::edit',compact('accounts','fund_transfer'));
    }

    public function update(StoreRequest $request)
    {
        if(env('DEMO')){
            Toastr::error(__('update_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }
        $account       = $this->accountRepo->getFind($request->from_account);
        $fund_transfer = $this->repo->getFind($request->id);
        $balance       = ($account->balance + $fund_transfer->amount);
        if($balance < $request->amount):
            Toastr::warning(__('not_enough_balance'),__('warning'));
            return redirect()->back()->withInput($request->all());
        endif;

        if($request->amount <= 0):
            Toastr::warning(__('more_than_0_amount'),__('warning'));
            return redirect()->back()->withInput($request->all());
        endif;

        if($this->repo->update($request->id,$request)){
            Toastr::success(__('fund_transfered_update_successfully'),__('success'));
            return redirect()->route('accounts.fund.transfer.index');
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
            Toastr::success(__('fund_transfer_deleted_successfully'),__('success'));
            return redirect()->route('accounts.fund.transfer.index');
        }else{
            Toastr::error(__('error'),__('errors'));
            return redirect()->back();
        }
    }
}
