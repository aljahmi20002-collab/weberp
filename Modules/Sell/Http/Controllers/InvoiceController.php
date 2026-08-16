<?php

namespace Modules\Sell\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Pos\Repositories\PosInterface;
use Modules\Purchase\Repositories\PurchaseInterface;
use Modules\Purchase\Repositories\PurchaseReturn\PurchaseReturnInterface;
use Modules\Sell\Repositories\SaleInterface;
use Modules\ServiceSale\Repositories\ServiceSaleInterface;
use Yajra\DataTables\DataTables;

class InvoiceController extends Controller
{

    protected $saleRepo,$posRepo,$purchaseRepo,$purchaseReturnRepo,$serviceSaleRepo;
    public function __construct(
        SaleInterface     $saleRepo, 
        PosInterface      $posRepo,
        PurchaseInterface $purchaseRepo,
        PurchaseReturnInterface $purchaseReturnRepo,
        ServiceSaleInterface $serviceSaleRepo
    )
    {
        $this->saleRepo           = $saleRepo; 
        $this->posRepo            = $posRepo;
        $this->purchaseRepo       = $purchaseRepo;
        $this->purchaseReturnRepo = $purchaseReturnRepo;
        $this->serviceSaleRepo    = $serviceSaleRepo;
    }

    public function index(Request $request){
        return view('sell::invoice.invoice_main',compact('request'));
    }

    public function getAllSaleInvoice(){
        $invoices               = $this->saleRepo->getInvoice();
        return DataTables::of($invoices)
        ->addIndexColumn() 
        ->editColumn('date',function($sale){
            return \Carbon\Carbon::parse($sale->created_at)->format('d-m-Y');
        })
        ->editColumn('invoice_no',function($sale){
            return ' <a href="'. route('invoice.view',$sale->id) .'" class="text-primary">'.@$sale->invoice_no .'</a>';
        })
        ->editColumn('branch',function($sale){
            return @$sale->branch->name;
        })
        ->editColumn('customer_details',function($sale){
            $customerDetails  = '';
            $customerDetails  .= '<div class="d-flex">'; 
            $customerDetails  .= '<span>'. __('type') .'</span>    :'. __(\Config::get('pos_default.customer_type.'.@$sale->customer_type));
            $customerDetails  .=' </div>';
            if($sale->customer_type == \Modules\Customer\Enums\CustomerType::EXISTING_CUSTOMER):
                $customerDetails  .= '<div class="d-flex"> ';
                $customerDetails  .= '<span>'.__('name') .'</span>    :'. @$sale->customer->name;
                $customerDetails  .= '</div>';
                $customerDetails  .= '<div class="d-flex">';
                $customerDetails  .= '<span>'. __('email') .'</span> :'. @$sale->customer->email;
                $customerDetails  .= '</div>';
                $customerDetails  .= '<div class="d-flex">';
                $customerDetails  .= '<span>'.__('phone') .'</span>  :'. @$sale->customer->phone;
                $customerDetails  .= '</div>'; 
                $customerDetails  .= '<div class="d-flex">';
                $customerDetails  .= '<span>'. __('address') .'</span>  :'. '<span class="address"> '. @$sale->customer->address .'</span>';
                $customerDetails  .= '</div>'; 
            endif;

            return $customerDetails;
        })
        ->editColumn('payment_status',function($sale){
            return @$sale->my_payment_status;
        })
        ->editColumn('total',function($sale){
            $total = '';
            $total .= '<span>'. __('total') .':</span>'.  @businessCurrency($sale->business_id) .' '. @number_format($sale->total_sale_price,2) .'<br/>';
            $total .= '<span class="text-success">'.__('paid') .':</span>'. @businessCurrency($sale->business_id).' '. @number_format($sale->payments->sum('amount'),2) .'<br/>';
            $total .= '<span class="text-danger">'. __('due') .':</span>'. @businessCurrency($sale->business_id) .' '. number_format($sale->DueAmount,2);
            return $total;
        })
        ->editColumn('action',function($sale){
            $action  = '';
            if(hasPermission('invoice_view') || hasPermission('sale_read_payment') ): 
                $action  .= '<div class="dropdown ">';
                $action  .= '<a href="#" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                $action  .=' <i class="fa fa-cogs"></i>';
                $action  .= '</a>';
                $action  .= '<div class="dropdown-menu todo-dropdown-menu" aria-labelledby="dropdownMenuButton"> ';
                        if(hasPermission('invoice_view') ):
                            $action  .= '<a href="'.route('invoice.view',$sale->id) .'" data-title="'. __('view') .'" class="dropdown-item "  ><i class="fa fa-eye"></i>'. __('view') .'</a>';  
                        endif;
                        
                        if(hasPermission('sale_read_payment') ):
                            $action  .= '<a href="#" data-title="'. __('manage_sale_payment') .'" class="dropdown-item modalBtn" data-modalsize="modal-xl" data-bs-toggle="modal" data-bs-target="#dynamic-modal"  data-url="'. route('sale.manage.payment',$sale->id) .'"><i class="fa fa-hand-holding-dollar"></i>'. __('payment') .'</a>';  
                        endif;
                        $action  .= '</div>';
                        $action  .= '</div> ';
            else:
                return '...'; 
            endif;
            return $action;
        })
        ->rawColumns(['date','invoice_no','branch','customer_details','payment_status','total','action'])
        ->make(true);
    }
    public function getAllPosInvoice(){
        $posinvoices            = $this->posRepo->getInvoice();
        return DataTables::of($posinvoices)
        ->addIndexColumn() 
        ->editColumn('date',function($pos){
            return \Carbon\Carbon::parse($pos->created_at)->format('d-m-Y');
        })
        ->editColumn('invoice_no',function($pos){
            return '<a href="'. route('pos.invoice.view',$pos->id) .'" class="text-primary">'.@$pos->invoice_no .'</a>';
        })
        ->editColumn('branch',function($pos){
            return @$pos->branch->name;
        })
        ->editColumn('customer_details',function($pos){
            $customerDetails  = '';
            $customerDetails  .=' <div class="d-flex"> ';
            $customerDetails  .= '<span>'.__('type') .'</span>    :'.  __(\Config::get('pos_default.customer_type.'.@$pos->customer_type));
            $customerDetails  .= '</div>';
            if($pos->customer_type == \Modules\Customer\Enums\CustomerType::EXISTING_CUSTOMER):
                $customerDetails  .= '<div class="d-flex"> ';
                $customerDetails  .= '<span>'. __('name') .'</span>    : '.@$pos->customer->name;
                $customerDetails  .= '</div>';
                $customerDetails  .= '<div class="d-flex">';
                $customerDetails  .=' <span>'. __('email') .'</span> :'. @$pos->customer->email;
                $customerDetails  .= '</div>';
                $customerDetails  .= '<div class="d-flex">';
                $customerDetails  .= '<span>'. __('phone') .'</span>  :'. @$pos->customer->phone;
                $customerDetails  .= '</div>'; 
                $customerDetails  .= '<div class="d-flex">';
                $customerDetails  .= '<span>'. __('address') .'</span>  : <span class="address">'. @$pos->customer->address .'</span>';
                $customerDetails  .= '</div>'; 
            endif;

            return $customerDetails;
        })
        ->editColumn('payment_status',function($pos){
            return @$pos->my_payment_status;
        })
        ->editColumn('total',function($pos){
            $total = '';
            $total .= '<span>'. __('total') .':</span> '. @businessCurrency($pos->business_id).' '. @number_format($pos->total_sale_price,2) .'<br/>';
            $total .= '<span class="text-success">'. __('paid') .':</span> '. @businessCurrency($pos->business_id).' '. @number_format($pos->payments->sum('amount'),2) .'<br/>';
            $total .= '<span class="text-danger">'. __('due') .':</span>'.  @businessCurrency($pos->business_id).' '. number_format($pos->DueAmount,2);
            return $total;
        })
        ->editColumn('action',function($pos){
            $action  = '';
            if(hasPermission('invoice_view') || hasPermission('pos_read_payment')):  
                $action .= '<div class="dropdown ">';
                $action .=  '<a href="#" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                $action .=  '<i class="fa fa-cogs"></i>';
                $action .= '</a>';
                $action .=  '<div class="dropdown-menu todo-dropdown-menu" aria-labelledby="dropdownMenuButton">'; 
                    if(hasPermission('invoice_view')):
                        $action .=  '<a href="'. route('pos.invoice.view',$pos->id) .'" data-title="'. __('view') .'" class="dropdown-item "  ><i class="fa fa-eye"></i>'. __('view') .'</a>';  
                    endif;
                    
                    if(hasPermission('pos_read_payment')):
                        $action .=  '<a href="#" data-title="'. __('manage_pos_payment') .'" class="dropdown-item modalBtn" data-modalsize="modal-xl" data-bs-toggle="modal" data-bs-target="#dynamic-modal"  data-url="'. route('pos.manage.payment',$pos->id) .'"><i class="fa fa-hand-holding-dollar"></i>'. __('payment') .'</a>'; 
                    endif;
                    $action .=  '</div>';
                    $action .=  '</div> '; 
            else:
                return '...';
            endif;

            return $action;
        })
        ->rawColumns(['date','invoice_no', 'branch', 'customer_details', 'payment_status', 'total', 'action'])
        ->make(true);
    }


    public function getAllPurchaseInvoice(){ 
        $purchaseInvoices       = $this->purchaseRepo->getInvoice();
        return DataTables::of($purchaseInvoices)
        ->addIndexColumn()
            
        ->editColumn('date',function($purchase){
            return \Carbon\Carbon::parse($purchase->created_at)->format('d-m-Y');
        })
        ->editColumn('invoice_no',function($purchase){
            return '<a href="'. route('purchase.invoice.view',$purchase->id) .'" class="text-primary">'. @$purchase->purchase_no .'</a>';
        })
        ->editColumn('branch',function($purchase){
            $branches = '';
            foreach ($purchase->PurchasedBranch as $branch){
                $branches .= '<span class="badge badge-pill  badge-primary">'. @$branch->name .'</span>';
            } 
            return $branches; 
        })
        ->editColumn('supplier_details',function($purchase){
            $supplierDetails = '';
            $supplierDetails .= '<div class="d-flex">';
            $supplierDetails .= '<span>'.__('name') .'</span>    : '. @$purchase->supplier->name;
            $supplierDetails .= '</div>';
            $supplierDetails .= '<div class="d-flex">';
            $supplierDetails .= '<span>'. __('company') .'</span> :'. @$purchase->supplier->company_name;
            $supplierDetails .= '</div>';
            $supplierDetails .= '<div class="d-flex">';
            $supplierDetails .=  '<span>'. __('phone') .'</span>  :'. @$purchase->supplier->phone;
            $supplierDetails .= '</div>';
            $supplierDetails .= '<div class="d-flex">';
            $supplierDetails .= '<span>'. __('address') .'</span>  : <span class="address">'. @$purchase->supplier->address .'</span>';
            $supplierDetails .= '</div> ';

            return $supplierDetails;
        })
        ->editColumn('payment_status',function($purchase){
            return  @$purchase->my_payment_status;
        })
        ->editColumn('total',function($purchase){
            $total = '';
            $total .=  '<span>'. __('total') .':</span>'. @businessCurrency($purchase->business_id).' '. @number_format($purchase->total_purchase_cost,2) .'<br/>';
            $total .=  '<span class="text-success">'. __('paid') .':</span> '. @businessCurrency($purchase->business_id) .' '. @number_format($purchase->payments->sum('amount'),2) .'<br/>';
            $total .=  '<span class="text-danger">'.__('due') .':</span>'. @businessCurrency($purchase->business_id) .' '. number_format($purchase->DueAmount,2);
            return $total;
        })
        ->editColumn('action',function($purchase){
            $action  = '';
            if(hasPermission('invoice_view') || hasPermission('purchase_read_payment')):  
                $action .= '<div class="dropdown ">';
                $action .= '<a href="#" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                $action .= '<i class="fa fa-cogs"></i>';
                $action .= '</a>';
                $action .= ' <div class="dropdown-menu todo-dropdown-menu" aria-labelledby="dropdownMenuButton">'; 
                    if(hasPermission('invoice_view')):
                        $action .= '<a href="'. route('purchase.invoice.view',$purchase->id).'" data-title="'. __('view') .'" class="dropdown-item "  ><i class="fa fa-eye"></i>'.__('view') .'</a>';  
                    endif;
                    if(hasPermission('purchase_read_payment')):
                        $action .= '<a href="#" data-title="'. __('manage_payment') .'" class="dropdown-item modalBtn" data-modalsize="modal-xl" data-bs-toggle="modal" data-bs-target="#dynamic-modal"  data-url="'. route('purchase.manage.payment',$purchase->id) .'"><i class="fa fa-hand-holding-dollar"></i>'. __('payment') .'</a> '; 
                    endif;
                    $action .= '</div>';
                    $action .= '</div> '; 
            else:
                return '...';
            endif;
            return $action;
        })
        ->rawColumns(['date','invoice_no','branch','supplier_details','payment_status','total','action'])
        ->make(true);
    }

    public function getAllPurchaseReturnInvoice(){ 
        $purchaseReturnInvoices = $this->purchaseReturnRepo->getInvoice();
        return DataTables::of($purchaseReturnInvoices)
        ->addIndexColumn() 
        ->editColumn('date',function($return){
            return \Carbon\Carbon::parse($return->created_at)->format('d-m-Y');
        })
        ->editColumn('invoice_no',function($return){
            return '<a href="'. route('purchase.return.invoice.view',$return->id) .'" class="text-primary">'.@$return->return_no .'</a>';
        })
        ->editColumn('branch',function($return){
            $branches = '';
            foreach ($return->PurchasedReturnBranch as $branch){
                $branches .= '<span class="badge badge-pill  badge-primary">'. @$branch->name .'</span>';
            } 
            return $branches;
        })
        ->editColumn('supplier_details',function($return){
            $supplierDetails = '';
            $supplierDetails .= '<div class="d-flex">';
            $supplierDetails .= '<span>'. __('name') .'</span>    : '. @$return->supplier->name;
            $supplierDetails .= '</div>';
            $supplierDetails .= '<div >';
            $supplierDetails .=  '<span>'.__('company') .'</span> :'. @$return->supplier->company_name;
            $supplierDetails .= '</div>';
            $supplierDetails .= '<div class=" ">';
            $supplierDetails .= '<span>'. __('phone') .'</span>  :'.  @$return->supplier->phone;
            $supplierDetails .= '</div> ';
            $supplierDetails .= '<div class="d-flex">';
            $supplierDetails .= '<span>'.__('address') .'</span>  : <span class="address">'. @$return->supplier->address .'</span>';
            $supplierDetails .= '</div> ';
            return $supplierDetails;
        })
        ->editColumn('payment_status',function($return){
            return @$return->my_payment_status;
        })
        ->editColumn('total',function($return){
            $total = '';
            $total .= '<span>'. __('total').':</span>'. @businessCurrency($return->business_id).' '. @number_format($return->total_purchase_return_price,2).'<br/>';
            $total .=' <span class="text-success">'. __('paid') .':</span>'. @businessCurrency($return->business_id) .' '. @number_format($return->payments->sum('amount'),2) .'<br/>';
            $total .= '<span class="text-danger">'. __('due') .':</span>'.  @businessCurrency($return->business_id) .' '. number_format($return->DueAmount,2);
            return $total;
        })
        ->editColumn('action',function($return){
            $action = '';
            if(hasPermission('invoice_view') || hasPermission('purchase_return_read_payment')): 
                $action .= '<div class="dropdown ">';
                $action .= '<a href="#" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                $action .= '<i class="fa fa-cogs"></i>';
                $action .= '</a>';
                $action .= '<div class="dropdown-menu todo-dropdown-menu" aria-labelledby="dropdownMenuButton"> ';
                    if(hasPermission('invoice_view')):
                        $action .= '<a href="'.route('purchase.return.invoice.view',$return->id) .'" data-title="'. __('view') .'" class="dropdown-item "  ><i class="fa fa-eye"></i>'.__('view') .'</a>';  
                    endif;
                    if(hasPermission('purchase_return_read_payment')):
                        $action .= '<a href="#" data-title="'.__('manage_payment') .'" class="dropdown-item modalBtn" data-modalsize="modal-xl" data-bs-toggle="modal" data-bs-target="#dynamic-modal"  data-url="'. route('purchase.return.manage.payment',$return->id) .'"><i class="fa fa-hand-holding-dollar"></i>'. __('payment') .'</a>';  
                    endif;
                    $action .= '</div>';
                    $action .= '</div>';
            else:
                return '...'; 
            endif;
            return $action;
        })
        ->rawColumns(['date','invoice_no','branch','supplier_details','payment_status','total','action'])
        ->make(true);
    }

    public function view($id){
        $sale      = $this->saleRepo->getFind($id);
        return view('sell::invoice.view',compact('sale'));
    }
 
    public function posInvoiceView($id){
        $pos      = $this->posRepo->getFind($id);
        return view('pos::invoice.view',compact('pos'));
    }

    public function serviceSaleview($id){
        $sale      = $this->serviceSaleRepo->getFind($id);
        return view('servicesale::invoice.view',compact('sale'));
    }

    public function getAllServiceSaleInvoice(){
        $invoices               = $this->serviceSaleRepo->get();
        return DataTables::of($invoices)
        ->addIndexColumn() 
        ->editColumn('date',function($sale){
            return \Carbon\Carbon::parse($sale->created_at)->format('d-m-Y');
        })
        ->editColumn('invoice_no',function($sale){
            return ' <a href="'. route('servicesale.invoice.view',$sale->id) .'" class="text-primary">'.@$sale->invoice_no .'</a>';
        })
        ->editColumn('branch',function($sale){
            return @$sale->branch->name;
        })
        ->editColumn('customer_details',function($sale){
            $customerDetails  = '';
            $customerDetails  .= '<div class="d-flex">'; 
            $customerDetails  .= '<span>'. __('type') .'</span>    :'. __(\Config::get('pos_default.customer_type.'.@$sale->customer_type));
            $customerDetails  .=' </div>';
            if($sale->customer_type == \Modules\Customer\Enums\CustomerType::EXISTING_CUSTOMER):
                $customerDetails  .= '<div class="d-flex"> ';
                $customerDetails  .= '<span>'.__('name') .'</span>    :'. @$sale->customer->name;
                $customerDetails  .= '</div>';
                $customerDetails  .= '<div class="d-flex">';
                $customerDetails  .= '<span>'. __('email') .'</span> :'. @$sale->customer->email;
                $customerDetails  .= '</div>';
                $customerDetails  .= '<div class="d-flex">';
                $customerDetails  .= '<span>'.__('phone') .'</span>  :'. @$sale->customer->phone;
                $customerDetails  .= '</div>'; 
                $customerDetails  .= '<div class="d-flex">';
                $customerDetails  .= '<span>'. __('address') .'</span>  :'. '<span class="address"> '. @$sale->customer->address .'</span>';
                $customerDetails  .= '</div>'; 
            endif;

            return $customerDetails;
        })
        ->editColumn('payment_status',function($sale){
            return @$sale->my_payment_status;
        })
        ->editColumn('total',function($sale){
            $total = '';
            $total .= '<span>'. __('total') .':</span>'.  @businessCurrency($sale->business_id) .' '. @number_format($sale->total_sale_price,2) .'<br/>';
            $total .= '<span class="text-success">'.__('paid') .':</span>'. @businessCurrency($sale->business_id).' '. @number_format($sale->payments->sum('amount'),2) .'<br/>';
            $total .= '<span class="text-danger">'. __('due') .':</span>'. @businessCurrency($sale->business_id) .' '. number_format($sale->DueAmount,2);
            return $total;
        })
        ->editColumn('action',function($sale){
            $action  = '';
            if(hasPermission('invoice_view') || hasPermission('service_sale_read_payment') ): 
                $action  .= '<div class="dropdown ">';
                $action  .= '<a href="#" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                $action  .=' <i class="fa fa-cogs"></i>';
                $action  .= '</a>';
                $action  .= '<div class="dropdown-menu todo-dropdown-menu" aria-labelledby="dropdownMenuButton"> ';
                        if(hasPermission('invoice_view') ):
                            $action  .= '<a href="'.route('servicesale.invoice.view',$sale->id) .'" data-title="'. __('view') .'" class="dropdown-item "  ><i class="fa fa-eye"></i>'. __('view') .'</a>';  
                        endif;
                        
                        if(hasPermission('service_sale_read_payment') ):
                            $action  .= '<a href="#" data-title="'. __('manage_service_sale_payment') .'" class="dropdown-item modalBtn" data-modalsize="modal-xl" data-bs-toggle="modal" data-bs-target="#dynamic-modal"  data-url="'. route('servicesale.manage.payment',$sale->id) .'"><i class="fa fa-hand-holding-dollar"></i>'. __('payment') .'</a>';  
                        endif;
                        $action  .= '</div>';
                        $action  .= '</div> ';
            else:
                return '...'; 
            endif;
            return $action;
        })
        ->rawColumns(['date','invoice_no','branch','customer_details','payment_status','total','action'])
        ->make(true);
    }
 
}
