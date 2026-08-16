<?php

namespace Modules\Supplier\Http\Controllers;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller; 
use Modules\Business\Repositories\BusinessInterface;
use Modules\Purchase\Repositories\PurchaseInterface;
use Modules\Purchase\Repositories\PurchaseReturn\PurchaseReturnInterface;
use Modules\Supplier\Http\Requests\StoreRequest;
use Modules\Supplier\Repositories\SupplierInterface;
use Yajra\DataTables\DataTables;

class SupplierController extends Controller
{

    protected $repo,$businessRepo,$purchaseRepo,$purchaseReturnRepo;
    public function __construct(SupplierInterface $repo,BusinessInterface $businessRepo,PurchaseInterface $purchaseRepo,PurchaseReturnInterface $purchaseReturnRepo)
    {
        $this->repo               = $repo;
        $this->businessRepo       = $businessRepo; 
        $this->purchaseRepo       = $purchaseRepo;
        $this->purchaseReturnRepo = $purchaseReturnRepo;
    }
    public function index()
    { 
        $suppliers   = $this->repo->get();
        return view('supplier::index',compact('suppliers'));
    }
 
    public function create()
    {
        $businesses = $this->businessRepo->getAll(); 
        return view('supplier::create',compact('businesses'));
    }

    public function store(StoreRequest $request)
    {
        if(env('DEMO')) {
            Toastr::error(__('store_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }

        if($this->repo->store($request)):
            Toastr::success(__('supplier_store_successfully'), __('success'));
            return redirect()->route('suppliers.index');
        else:
            Toastr::error(__('error'), __('errors'));
            return redirect()->back();
        endif;

    }
 
    public function edit($id)
    {
        $supplier  = $this->repo->getFind($id);
        $businesses = $this->businessRepo->getAll(); 
        return view('supplier::edit',compact('supplier','businesses'));
    }
 
    public function update(StoreRequest $request)
    {
        if(env('DEMO')) {
            Toastr::error(__('update_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }

        if($this->repo->update($request->id,$request)):
            Toastr::success(__('supplier_update_successfully'), __('success'));
            return redirect()->route('suppliers.index');
        else:
            Toastr::error(__('error'), __('errors'));
            return redirect()->back();
        endif;
    }

    public function destroy($id)
    {
        if($this->repo->delete($id)):
            Toastr::success(__('supplier_delete_successfully'), __('success'));
            return redirect()->back();
        else:
            Toastr::error(__('error'), __('errors'));
            return redirect()->back();
        endif;
    }

    public function statusUpdate($id)
    {
        if(env('DEMO')) {
            Toastr::error(__('update_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }

        if($this->repo->statusUpdate($id)):
            Toastr::success(__('supplier_updated_successfully'), __('success'));
            return redirect()->route('suppliers.index');
        else:
            Toastr::error(__('error'), __('errors'));
            return redirect()->back();
        endif;
    }
 
    public function view(Request $request,$supplier_id){
        $supplier              = $this->repo->getFind($supplier_id);
        $purchaseTotal         = $this->purchaseRepo->supplierWisePurchase($supplier_id); 
        $purchaseReturnTotal   = $this->purchaseReturnRepo->supplierWisePurchaseReturn($supplier_id); 
        return view('supplier::view',compact('supplier','request','purchaseTotal','purchaseReturnTotal'));
    } 

    public function  getPurchaseInvoice ($supplier_id){
        $purchaseInvoices      = $this->purchaseRepo->supplierWisePurchaseList($supplier_id);
        return DataTables::of($purchaseInvoices)
        ->addIndexColumn() 
        ->editColumn('date',function($purchase){
            return \Carbon\Carbon::parse($purchase->created_at)->format('d-m-Y');
        })
        ->editColumn('invoice_no',function($purchase){
            return  '<a href="'. route('purchase.invoice.view',$purchase->id) .'" class="text-primary">'. @$purchase->purchase_no .'</a>';
        })
        ->editColumn('branch',function($purchase){
            $branches = '';
            foreach ($purchase->PurchasedBranch as $key=>$branch){
                $branches .= $branch->name;
                if(($key+1) < $purchase->PurchasedBranch->count()):
                    $branches  .=',';
                endif;
            }
            return $branches; 
        })
        ->editColumn('payment_status',function($purchase){
            return @$purchase->my_payment_status;
        })
        ->editColumn('total',function($purchase){
            $total = '';
            $total .= '<span>'. __('total') .':</span> '. @businessCurrency($purchase->business_id). @number_format($purchase->total_purchase_cost,2) .'<br/>';
            $total .= '<span class="text-success">'.__('paid') .':</span>'.  @businessCurrency($purchase->business_id) . @number_format($purchase->payments->sum('amount'),2).'<br/>'.
            '<span class="text-danger">'. __('due') .':</span> '. @businessCurrency($purchase->business_id). number_format($purchase->DueAmount,2);
            return $total;
        })
        ->editColumn('action',function($purchase){
            $action  = '';
            $action .= '<div class="dropdown ">';
            $action .= '<a href="#" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
            $action .= '<i class="fa fa-cogs"></i>';
            $action .= '</a>';
            $action .= '<div class="dropdown-menu todo-dropdown-menu" aria-labelledby="dropdownMenuButton">';  
            $action .=  '<a href="'.route('purchase.invoice.view',$purchase->id) .'"  class="dropdown-item "  ><i class="fa fa-eye"></i>'. __('view') .'</a>  ';
                    
            if(hasPermission('purchase_read_payment')):
                $action .=  '<a href="#" data-title="'. __('manage_payment') .'" class="dropdown-item modalBtn" data-modalsize="modal-xl" data-bs-toggle="modal" data-bs-target="#dynamic-modal"  data-url="'. route('purchase.manage.payment',$purchase->id) .'"> <i class="fa fa-hand-holding-dollar"></i>'. __('payment') .'</a>';  
            endif;

            $action .= '</div>';
            $action .= '</div>';
            return $action;
        })

        ->rawColumns(['date','invoice_no','branch','payment_status','total','action'])
        ->make(true);
    }
    public function  getPurchaseInvoicePayment($supplier_id){
        $invoicePaymentHistory = $this->purchaseRepo->supplierWisePaymentList($supplier_id); 
        return DataTables::of($invoicePaymentHistory)
        ->addIndexColumn() 
        ->editColumn('date',function($payment){
            return \Carbon\Carbon::parse($payment->paid_date)->format('d-m-Y');
        })
        ->editColumn('invoice_no',function($payment){
            return '<a href="'. route('purchase.invoice.view',$payment->purchase_id) .'" class="text-primary">'. @$payment->purchase->purchase_no .'</a>';
        })
        ->editColumn('payment_info',function($payment){
                $paymentInfo   = '';
                $paymentInfo .= '<div class="d-flex">';
                $paymentInfo .=' <b>'. __('payment_method') .'</b>:'. __(\Config::get('pos_default.purchase.payment_method.'.$payment->payment_method));
                $paymentInfo .= '</div>';
            if($payment->payment_method == \Modules\Purchase\Enums\PaymentMethod::BANK):
                $paymentInfo .= '<div class="d-flex">';
                $paymentInfo .= '<b>'. __('holder_name') .'</b>:'. $payment->bank_holder_name ;
                $paymentInfo .= '</div>';
                $paymentInfo .= '<div class="d-flex">';
                $paymentInfo .= '<b>'.__('account_no') .'</b>:'. $payment->bank_account_no;
                $paymentInfo .= '</div>';
            endif;
            return $paymentInfo;
        })
        ->editColumn('amount',function($payment){
            return @businessCurrency($payment->purchase->business_id) .' '.@$payment->amount;
        })
        ->editColumn('document',function($payment){
            return '<a href="'.@$payment->documents .'">'. __('download').'</a>';
        })
        ->editColumn('description',function($payment){
            return $payment->description;
        })
        ->editColumn('action',function($payment){
            $action = '';
            $action .= '<div class="dropdown ">';
            $action .= '<a href="#" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
            $action .=' <i class="fa fa-cogs"></i>';
            $action .= '</a>';
            $action .= '<div class="dropdown-menu todo-dropdown-menu" aria-labelledby="dropdownMenuButton">  ';
            $action .= '<a href="'. route('purchase.invoice.view',$payment->purchase_id) .'" class="dropdown-item "  ><i class="fa fa-file-invoice-dollar"></i>'. __('invoice') .'</a> '; 
            $action .= '</div>';
            $action .= '</div>';
            return $action;
        })

        ->rawColumns(['date','invoice_no','payment_info','amount','document','description','action'])
        ->make(true);
    }
    public function  getPurchaseReturnInvoice($supplier_id){
        $returnPurchaseInvoices = $this->purchaseReturnRepo->supplierWisePurchaseList($supplier_id); 
        return DataTables::of($returnPurchaseInvoices)
        ->addIndexColumn() 
        ->editColumn('date',function($purchaseReturn){
            return \Carbon\Carbon::parse($purchaseReturn->created_at)->format('d-m-Y');
        })
        ->editColumn('invoice_no',function($purchaseReturn){
            return '<a href="'.route('purchase.return.invoice.view',$purchaseReturn->id) .'" class="text-primary">'. @$purchaseReturn->return_no .'</a>';
        })
        ->editColumn('branch',function($purchaseReturn){
            $branches = '';
            foreach ($purchaseReturn->PurchasedReturnBranch as $key=>$branch){
                $branches .=  $branch->name;
                if(($key+1) < $purchaseReturn->PurchasedReturnBranch->count()):
                    $branches .=',';
                endif;
            }
            return $branches;
                
        })
        ->editColumn('payment_status',function($purchaseReturn){
            return @$purchaseReturn->my_payment_status;
        })
        ->editColumn('total',function($purchaseReturn){
            $total = '';
            $total .= '<span>'.__('total') .':</span> '. @businessCurrency($purchaseReturn->business_id).' '. @number_format($purchaseReturn->total_purchase_cost,2) .'<br/>';
            $total .=  '<span class="text-success">'. __('paid').':</span> '. @businessCurrency($purchaseReturn->business_id).' '. @number_format($purchaseReturn->payments->sum('amount'),2) .'<br/>';
            $total .= '<span class="text-danger">'.__('due') .':</span>'.  @businessCurrency($purchaseReturn->business_id) .' '.number_format($purchaseReturn->DueAmount,2);
            return $total;
        })
        ->editColumn('action',function($purchaseReturn){
            $action  = '';
            $action  .= '<div class="dropdown ">';
            $action  .= '<a href="#" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
            $action  .= '<i class="fa fa-cogs"></i>';
            $action  .= '</a>';
            $action  .= '<div class="dropdown-menu todo-dropdown-menu" aria-labelledby="dropdownMenuButton">';  
            $action  .= '<a href="'.route('purchase.return.invoice.view',$purchaseReturn->id) .'"  class="dropdown-item "  ><i class="fa fa-eye"></i>'. __('view') .'</a> ';
                    
            if(hasPermission('purchase_return_read_payment')):
                $action  .= '<a href="#" data-title="'. __('manage_return_payment') .'" class="dropdown-item modalBtn" data-modalsize="modal-xl" data-bs-toggle="modal" data-bs-target="#dynamic-modal"  data-url="'. route('purchase.return.manage.payment',$purchaseReturn->id) .'"><i class="fa fa-hand-holding-dollar"></i>'. __('payment') .'</a> '; 
            endif;

            $action  .= '</div>';
            $action  .= '</div>'; 

            return $action;
        })

        ->rawColumns(['date','invoice_no', 'branch', 'payment_status', 'total', 'action'])
        ->make(true);
    }
    public function  getPurchaseReturnInvoicePayment($supplier_id){
        $ReturnPaymentHistory   = $this->purchaseReturnRepo->supplierWisePurchaseReturnPaymentList($supplier_id); 
        return DataTables::of($ReturnPaymentHistory)
        ->addIndexColumn() 
        ->editColumn('date',function($payment){
            return \Carbon\Carbon::parse($payment->paid_date)->format('d-m-Y');
        })
        ->editColumn('invoice_no',function($payment){
            return '<a href="'. route('purchase.return.invoice.view',$payment->purchase_return_id) .'" class="text-primary">'.@$payment->purchasereturn->return_no .'</a>';
        })
        ->editColumn('payment_info',function($payment){
            $paymentInfo    = '';
            $paymentInfo   .= '<div class="d-flex">';
            $paymentInfo   .= ' <b>'.__('payment_method').'</b>:'.__(\Config::get('pos_default.purchase.payment_method.'.$payment->payment_method));
            $paymentInfo   .= '</div>';
            if($payment->payment_method == \Modules\Purchase\Enums\PaymentMethod::BANK):
                $paymentInfo   .= '<div class="d-flex">';
                $paymentInfo   .= '<b>'.__('holder_name').'</b>:'.$payment->bank_holder_name;
                $paymentInfo   .= '</div>';
                $paymentInfo   .= '<div class="d-flex">';
                $paymentInfo   .= '<b>'. __('account_no') .'</b>:'. $payment->bank_account_no;
                $paymentInfo   .= '</div>';
            endif;
            return $paymentInfo;
        })
        ->editColumn('amount',function($payment){
            return @businessCurrency($payment->purchasereturn->business_id).' '.@$payment->amount;
        })
        ->editColumn('document',function($payment){
            return '<a href="'. @$payment->documents .'">'.__('download') .'</a>';
        })
        ->editColumn('description',function($payment){
            return $payment->description;
        })
        ->editColumn('action',function($payment){
            $action = '';
            $action .= '<div class="dropdown ">';
            $action .= '<a href="#" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
            $action .= '<i class="fa fa-cogs"></i>';
            $action .= '</a>';
            $action .= '<div class="dropdown-menu todo-dropdown-menu" aria-labelledby="dropdownMenuButton">';
            $action .= '<a href="'.route('purchase.return.invoice.view',$payment->purchase_return_id) .'"  class="dropdown-item" > <i class="fa fa-file-invoice-dollar"></i>'. __('invoice') .'</a>'; 
            $action .= '</div>';
            $action .= '</div>';
            return $action;
        }) 
        ->rawColumns(['date','invoice_no','payment_info','amount','document','description','action'])
        ->make(true);
    }

    public function createModal(){
        $businesses = $this->businessRepo->getAll(); 
        return view('supplier::create_modal',compact('businesses'));
    } 

    public function storeModal(StoreRequest $request){
        if(env('DEMO')) {
            Toastr::error(__('store_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        } 
        if($this->repo->store($request)):
            Toastr::success(__('supplier_store_successfully'), __('success'));
            return redirect()->back();
        else:
            Toastr::error(__('error'), __('errors'));
            return redirect()->back();
        endif;
    }
    
}
