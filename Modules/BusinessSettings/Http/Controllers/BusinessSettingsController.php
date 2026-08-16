<?php

namespace Modules\BusinessSettings\Http\Controllers;

use App\Enums\UserType;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\AccountHead\Repositories\AccountHeadInterface;
use Modules\Branch\Repositories\BranchInterface;
use Modules\Business\Repositories\BusinessInterface;
use Modules\BusinessSettings\Http\Requests\UpdateRequest;
use Modules\BusinessSettings\Repositories\BarcodeSettings\BarcodeSettingsInterface;
use Modules\BusinessSettings\Repositories\BusinessSettingsInterface;
use Modules\TaxRate\Repositories\TaxRateInterface;
use Yajra\DataTables\DataTables;

class BusinessSettingsController extends Controller
{
    protected $repo,$businessRepo,$branchRepo,$taxrateRepo,$accountHeadRepo,$barcodeSettingsRepo;
    public function __construct(
            BusinessSettingsInterface $repo,
            BusinessInterface $businessRepo,
            BranchInterface $branchRepo,
            TaxRateInterface $taxrateRepo,
            AccountHeadInterface $accountHeadRepo,
            BarcodeSettingsInterface $barcodeSettingsRepo
        )
    {
        $this->repo                = $repo;
        $this->businessRepo        = $businessRepo;
        $this->branchRepo          = $branchRepo;
        $this->taxrateRepo         = $taxrateRepo;
        $this->accountHeadRepo     = $accountHeadRepo;
        $this->barcodeSettingsRepo = $barcodeSettingsRepo;
    }
    public function index(Request $request)
    {  
        if(Auth::user()->business):
            $business_id = Auth::user()->business->id;
        else:
            $business_id = Auth::user()->userBusiness->id;
        endif;
        $business        = $this->businessRepo->getFind($business_id);
        return view('businesssettings::index',compact('business','request',));
    }

    public function  getBranch (){ 
        $branches        = $this->branchRepo->getBranch(business_id()); 
        return DataTables::of($branches)
        ->addIndexColumn() 
        ->editColumn('name',function($branch){
            return  @$branch->name;
        })
        ->editColumn('email',function($branch){
            return @$branch->email;
        })
        ->editColumn('website',function($branch){
            return @$branch->website;
        })
        ->editColumn('phone',function($branch){
            return  @$branch->phone;
        })
        ->editColumn('country',function($branch){
            return @$branch->country->country;
        })
        ->editColumn('state',function($branch){
            return @$branch->state;
        })
        ->editColumn('city',function($branch){
            return @$branch->city;
        })
        ->editColumn('zip_code',function($branch){
            return @$branch->zip_code;
        })
        ->editColumn('balance',function($branch){
            return businessCurrency($branch->business_id) .' '. @$branch->balance;
        })
        ->editColumn('status',function($branch){
            return  @$branch->my_status;
        })
        ->editColumn('action',function($branch){
            $action = '';
            if(hasPermission('branch_update') || hasPermission('branch_delete') || hasPermission('branch_status_update')):
            
                $action .= '<div class="dropdown">';
                $action .=  '<a href="#" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                $action .=  '<i class="fa fa-cogs"></i>';
                $action .= '</a>';
                $action .= '<div class="dropdown-menu todo-dropdown-menu" aria-labelledby="dropdownMenuButton">';
                    if(hasPermission('branch_status_update')):
                        $action .= '<a class="dropdown-item" href="'.route('settings.branch.status.update',$branch->id) .'">';
                        $action .= $branch->status == \App\Enums\Status::ACTIVE? '<i class="fa fa-ban"></i>'.@statusUpdate($branch->status):'<i class="fa fa-check"></i>'. @statusUpdate($branch->status);
                        $action .=  '</a>';
                    endif;

                    if(hasPermission('branch_update')):
                        $action .=  '<a href="#" class="dropdown-item modalBtn"  data-url="'.route('settings.branch.edit',$branch->id) .'" data-title="'. __('branch') .' '. __('edit').'" data-bs-toggle="modal" data-bs-target="#dynamic-modal" data-modalsize="modal-lg"><i class="fas fa-pen"></i>'. __('edit') .'</a>';
                    endif;
                    if(hasPermission('branch_delete')):
                        $action .= '<form id="delete" action="'.route('settings.branch.delete',@$branch->id) .'" method="post" class="delete-form"  data-yes='. __('yes') .' data-cancel="'. __('cancel') .'" data-title="'. __('delete_branch') .'">';
                        $action .= method_field('delete');
                        $action .= csrf_field();
                        $action .= '<button type="submit" class="dropdown-item"   >';
                        $action .= '<i class="fas fa-trash-alt"></i>'. __('delete');
                        $action .= '</button>';
                        $action .= '</form>';
                    endif; 

                    $action .= '</div>';
                    $action .= '</div> '; 
            else:
                return '...';
            endif;
            return $action;
        })
        ->rawColumns(['name', 'email', 'website', 'phone', 'country', 'state', 'city', 'zip_code', 'balance', 'status', 'action'])
        ->make(true);
    }
    public function  getTaxRate (){
        $taxRates        = $this->taxrateRepo->getTaxRate(); 
        return DataTables::of($taxRates) 
        ->addIndexColumn()
        ->editColumn('name',function($taxRate){
            return @$taxRate->name;
        })
        ->editColumn('tax_rate_parcentage',function($taxRate){
            return @$taxRate->tax_rate;
        })
        ->editColumn('position',function($taxRate){
            return @$taxRate->position;
        })
        ->editColumn('status',function($taxRate){
            return @$taxRate->my_status;
        })
        ->editColumn('action',function($taxRate){
            $action = '';
            if(hasPermission('tax_rate_update') || hasPermission('tax_rate_delete') || hasPermission('tax_rate_status_update')):
                if($taxRate->id != 1):
                    $action .= ' <div class="dropdown">';
                    $action .= '<a href="#" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                    $action .=  '<i class="fa fa-cogs"></i>';
                    $action .=  '</a>';
                    $action .=  '<div class="dropdown-menu todo-dropdown-menu" aria-labelledby="dropdownMenuButton">';
                        if(hasPermission('tax_rate_status_update')):
                            $action .= '<a class="dropdown-item" href="'. route('settings.tax.rate.status.update',$taxRate->id) .'">';
                            $action .=   $taxRate->status == \App\Enums\Status::ACTIVE? '<i class="fa fa-ban"></i>':'<i class="fa fa-check"></i>';
                            $action .= @statusUpdate($taxRate->status);
                            $action .= '</a>';
                        endif;
                        if(hasPermission('tax_rate_update')):
                            $action .=  '<a href="#" class="dropdown-item modalBtn" data-url="'. route('settings.tax.rate.edit',@$taxRate->id) .'" data-bs-toggle="modal" data-bs-target="#dynamic-modal" data-title="'.__('tax_rate').' '. __('edit') .'" ><i class="fas fa-pen" ></i>'.__('edit') .'</a>';
                        endif;
                
                            if(hasPermission('tax_rate_delete')):
                                $action .=  '<form action="'. route('settings.tax.rate.delete',@$taxRate->id) .'" method="post" class="delete-form" id="delete" data-yes='. __('yes') .' data-cancel="'. __('cancel') .'" data-title="'. __('delete_tax_rate') .'">';
                                $action .=  method_field('delete');
                                $action .=  csrf_field();
                                $action .=  '<button type="submit" class="dropdown-item "  >';
                                $action .=  '<i class="fas fa-trash-alt"></i>'. __('delete');
                                $action .= '</button>';
                                $action .=  '</form>';
                            endif;  
                    
                    $action .= '</div>';
                    $action .=  '</div>';   
                else:
                   return '<i class="fa fa-ellipsis"></i>';
                endif;
            else:
                return '...'; 
            endif;
            return $action;
        })
        ->rawColumns(['name',  'tax_rate_parcentage', 'position', 'status', 'action'])
        ->make(true);
    }
    public function  getAccountHead (){
        $accountHeads    = $this->accountHeadRepo->getAccountHead();
        return DataTables::of($accountHeads)
        ->addIndexColumn() 
        ->editColumn('name',function($accountHead){
            return @$accountHead->name;
        })
        ->editColumn('note',function($accountHead){
            return @$accountHead->note;
        })
        ->editColumn('type',function($accountHead){
            return @$accountHead->my_type;
        })
        ->editColumn('status',function($accountHead){
            return @$accountHead->my_status;
        })
        ->editColumn('action',function($accountHead){

            $action ='';
            if(hasPermission('account_head_update') || hasPermission('account_head_delete') || hasPermission('account_head_status_update')):
                $action     = '<div class="dropdown">';
                $action .= '<a href="#" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                $action .=  '<i class="fa fa-cogs"></i>';
                $action .=  '</a>';
                $action .= '<div class="dropdown-menu todo-dropdown-menu" aria-labelledby="dropdownMenuButton">';
                    if(hasPermission('account_head_status_update')):
                        $action .= '<a class="dropdown-item" href="'. route('accounts.account.head.status.update',$accountHead->id) .'">';
                        $action .=  $accountHead->status == \App\Enums\Status::ACTIVE? '<i class="fa fa-ban"></i>':'<i class="fa fa-check"></i>';
                        $action .= @statusUpdate($accountHead->status);
                        $action .= '</a>';
                    endif;
                    if(hasPermission('account_head_update')):
                        $action .= '<a href="#" class="dropdown-item modalBtn" data-bs-toggle="modal" data-bs-target="#dynamic-modal" data-url="'.route('accounts.account.head.edit',$accountHead->id) .'" data-title="'. __('account_head') .' '. __('edit') .'"><i class="fas fa-pen"></i>'. __('edit') .'</a>';
                    endif;
                    if(hasPermission('account_head_delete')):
                        $action .=  '<form id="delete" action="'. route('accounts.account.head.delete',@$accountHead->id) .'" method="post" class="delete-form"  data-yes='. __('yes').' data-cancel="'. __('cancel') .'" data-title="'. __('delete_account_head') .'">';
                        $action .= method_field('delete');
                        $action .= csrf_field();
                        $action .= '<button type="submit" class="dropdown-item" data-bs-toggle="tooltip"  >';
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
        ->rawColumns(['name', 'note', 'type', 'status', 'action'])
        ->make(true);
    }
    public function  getbarcodesettings (){ 
        $barcodeSettings = $this->barcodeSettingsRepo->getAll();
        return DataTables::of($barcodeSettings)
        ->addIndexColumn() 
        ->editColumn('name',function($barcode){
            return  @$barcode->name;
        })
        ->editColumn('paper_width',function($barcode){
            return @$barcode->paper_width;
        })
        ->editColumn('paper_height',function($barcode){
            return @$barcode->paper_height;
        })
        ->editColumn('label_width',function($barcode){
            return @$barcode->label_width;
        })
        ->editColumn('label_height',function($barcode){
            return @$barcode->label_height;
        })
        ->editColumn('label_in_per_row',function($barcode){
            return @$barcode->label_in_per_row;
        })
        ->editColumn('action',function($barcode){
            $action = '';
            $action .= '<div class="dropdown">';
            $action .= '<a href="#" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
            $action .= ' <i class="fa fa-cogs"></i>';
            $action .= '</a>';
            $action .= '<div class="dropdown-menu todo-dropdown-menu" aria-labelledby="dropdownMenuButton">  ';
            $action .= '<a href="#" class="dropdown-item modalBtn" data-url="'. route('settings.barcode.settings.edit',@$barcode->id) .'" data-bs-toggle="modal" data-bs-target="#dynamic-modal" data-title="'.__('barcode_settings') .' '.__('edit') .'" ><i class="fas fa-pen" ></i>'. __('edit') .'</a> ';
            $action .= ' <form action="'. route('settings.barcode.settings.delete',@$barcode->id) .'" method="post" class="delete-form" id="delete" data-yes='. __('yes') .' data-cancel="'. __('cancel') .'" data-title="'. __('delete_barcode_settings') .'">';
            $action .= method_field('delete');
            $action .= csrf_field();
            $action .= '<button type="submit" class="dropdown-item "  >';
            $action .= '<i class="fas fa-trash-alt"></i>'. __('delete');
            $action .= '</button>';
            $action .= '</form> ';
            $action .= '</div>';
            $action .= ' </div>'; 

            return $action;
        })

        ->rawColumns([ 'name', 'paper_width', 'paper_height', 'label_width', 'label_height', 'label_in_per_row', 'action'])
        ->make(true);
    }

    public function update(UpdateRequest $request){
        if(Auth::user()->user_type !== UserType::ADMIN):
            Toastr::error(__('error'),__('errors'));
            return redirect()->back();
        endif;
        if(env('DEMO')) {
            Toastr::error(__('update_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }
        if($this->repo->update($request)){
            Toastr::success(__('business_updated_successfully'),__('success'));
            return redirect()->route('settings.business.settings.index');
        }else{
            Toastr::error(__('error'),__('errors'));
            return redirect()->back();
        }
    }
}
