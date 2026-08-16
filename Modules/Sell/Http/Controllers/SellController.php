<?php

namespace Modules\Sell\Http\Controllers;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Sell\Repositories\SaleInterface;
use Illuminate\Support\Str;
use Modules\Branch\Repositories\BranchInterface;
use Modules\Customer\Enums\CustomerType;
use Modules\Customer\Repositories\CustomerInterface;
use Modules\Purchase\Repositories\PurchaseInterface;
use Modules\Sell\Http\Requests\Sale\AddPaymentRequest;
use Modules\Sell\Http\Requests\Sale\StoreRequest;
use Modules\TaxRate\Repositories\TaxRateInterface;
use Yajra\DataTables\DataTables;

class SellController extends Controller
{
    protected $repo,$taxRateRepo,$branchRepo,$customerRepo,$purchaseRepo;
    public function __construct(
            SaleInterface $repo,
            TaxRateInterface $taxRateRepo,
            BranchInterface $branchRepo,
            CustomerInterface $customerRepo,
            PurchaseInterface $purchaseRepo
        )
    {
        $this->repo         = $repo;
        $this->taxRateRepo  = $taxRateRepo;
        $this->branchRepo   = $branchRepo;
        $this->customerRepo = $customerRepo;
        $this->purchaseRepo = $purchaseRepo;
    }
    public function index()
    {
        return view('sell::sale.index');
    }

    public function getAllSale(){

        ini_set('memory_limit', '50012M');
        ini_set('max_execution_time', 90000000); //900 seconds 

        $sales   = $this->repo->getAllSale();
        return DataTables::of($sales)
        ->addIndexColumn()
        ->editColumn('date',function($sale){
            return \Carbon\Carbon::parse($sale->created_at)->format('d-m-Y h:i:s');
        })
        ->editColumn('invoice_no',function($sale){
            return @$sale->invoice_no;
        })
        ->editColumn('branch',function($sale){
            return  @$sale->branch->name;
        })
        ->editColumn('customer_details',function($sale){
            $customerDetails ='';
            $customerDetails .= '<div class="d-flex">';
            $customerDetails .=  '<span>'.__('type').'</span>    :'. __(\Config::get('pos_default.customer_type.'.@$sale->customer_type));
            $customerDetails .= '</div>';
            if($sale->customer_type == \Modules\Customer\Enums\CustomerType::EXISTING_CUSTOMER):
                $customerDetails .= '<div class="d-flex">';
                $customerDetails .= '<span>'.__('name').'</span>    :'. @$sale->customer->name;
                $customerDetails .=  '</div>';
                $customerDetails .= '<div class="d-flex">';
                $customerDetails .= '<span>'. __('email') .'</span> : '.@$sale->customer->email;
                $customerDetails .= '</div>';
                $customerDetails .= '<div class="d-flex">';
                $customerDetails .= '<span>'.__('phone').'</span>  :'. @$sale->customer->phone;
                $customerDetails .= '</div>';
                $customerDetails .= '<div class="d-flex">';
                $customerDetails .= '<span>'. __('address').'</span>  : <span class="address">'. @$sale->customer->address .'</span>';
                $customerDetails .= '</div>';
            elseif($sale->customer_type == CustomerType::WALK_CUSTOMER):
                $customerDetails .= '<div class="d-flex">';
                $customerDetails .= '<span>'. __('phone').'</span>  : <span class="address">'. @$sale->customer_phone .'</span>';
                $customerDetails .= '</div>';
            endif;
            return $customerDetails;
        })
        ->editColumn('payment_status',function($sale){
            return @$sale->my_payment_status;
        })
        ->editColumn('shipping_status',function($sale){
            return __(\Config::get('pos_default.shpping_status.'.$sale->shipping_status));
        })
        ->editColumn('total_sell_price',function($sale){
            return @businessCurrency($sale->business_id) .' '.@number_format($sale->total_sale_price,2) ;
        })
        ->editColumn('created_by',function($sale){
            return @$sale->user->name;
        })
        ->editColumn('action',function($sale){
            $action  = '';
            $action .= '<div class="dropdown">';
            $action .= '<a href="#" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
            $action .= '<i class="fa fa-cogs"></i>';
            $action .= '</a>';
            $action .= '<div class="dropdown-menu todo-dropdown-menu" aria-labelledby="dropdownMenuButton"> ';
            $action .= '<a href="'.route('sale.print',$sale->id) .'" data-title="'. __('print') .'" class="dropdown-item" target="_blank" ><i class="fa fa-print"></i>'. __('print') .'</a> ';
            $action .= '<a href="'. route('sale.invoice.view',$sale->id) .'" class="dropdown-item"  ><i class="fa fa-file-invoice-dollar"></i>'. __('invoice').'</a>';
            $action .= '<a href="#" class="dropdown-item modalBtn" data-url="'.route('sale.details',$sale->id) .'" data-title="'.__('sale_details') .'" data-bs-toggle="modal" data-bs-target="#dynamic-modal" data-modalsize="modal-xl" ><i class="fa fa-eye"></i> '. __('view') .'</a> ';
                if(hasPermission('sale_read_payment') ):
                    $action .= '<a href="#" data-title="'. __('manage_sale_payment') .'" class="dropdown-item modalBtn" data-modalsize="modal-xl" data-bs-toggle="modal" data-bs-target="#dynamic-modal"  data-url="'.route('sale.manage.payment',$sale->id) .'"><i class="fa fa-hand-holding-dollar"></i>'. __('payment').'</a>  ';
                endif;

                if(hasPermission('sale_update')):
                    $action .= '<a href="'. route('sale.edit',@$sale->id) .'" class="dropdown-item" ><i class="fas fa-pen"></i>'. __('edit') .'</a>';
                endif;
                if(hasPermission('sale_delete')):
                    $action .= '<form action="'. route('sale.delete',@$sale->id) .'" method="post" class="delete-form" id="delete"  data-yes='.__('yes') .' data-cancel="'. __('cancel') .'" data-title="'. __('delete_sale') .'">';
                    $action .= method_field('delete');
                    $action .= csrf_field();
                    $action .= '<button type="submit" class="dropdown-item">';
                    $action .= '<i class="fas fa-trash-alt"></i>'. __('delete');
                    $action .= '</button>';
                    $action .= '</form>';
                endif;
                $action .= '</div>';
                $action .= '</div>';
                return $action;
        })
        ->rawColumns(['date','invoice_no', 'branch', 'customer_details', 'payment_status', 'shipping_status','action'])
        ->make(true);
    }

    public function print($id){
        $sale      = $this->repo->getFind($id);
        return view('sell::sale.sale_print',compact('sale'));
    }

    public function mobilePrint($id){ 
         
        $sale      = $this->repo->getFind($id);
        return view('sell::sale.mobile_sale_print',compact('sale'));
    }


    public function create()
    {
        $branches      = $this->branchRepo->getBranches(business_id());
        $customers     = $this->customerRepo->getActiveCustomers(business_id());
        return view('sell::sale.create',compact('branches','customers'));
    }


    public function store(StoreRequest $request)
    {
        
        if(env('DEMO')) {
            Toastr::error(__('store_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }
        if($this->repo->store($request)):
            Toastr::success(__('sale_store_successfully'), __('success'));
            return redirect()->route('sale.index');
        else:
            Toastr::error(__('error'), __('errors'));
            return redirect()->back();
        endif;

    }

    public function edit($id)
    {
        $sale          = $this->repo->getFind($id);
        $branches      = $this->branchRepo->getBranches(business_id());
        $customers     = $this->customerRepo->getActiveCustomers(business_id());
        return view('sell::sale.edit',compact('branches','customers','sale'));
    }

    public function update(StoreRequest $request)
    {
        if(env('DEMO')) {
            Toastr::error(__('update_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }
        if($this->repo->update($request->id,$request)):
            Toastr::success(__('sale_update_successfully'), __('success'));
            return redirect()->route('sale.index');
        else:
            Toastr::error(__('error'), 'error');
            return redirect()->back();
        endif;
    }

    public function destroy($id)
    {
        if(env('DEMO')) {
            Toastr::error(__('delete_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }
        if($this->repo->delete($id)):
            Toastr::success(__('sale_delete_successfully'), __('success'));
            return redirect()->route('sale.index');
        else:
            Toastr::error(__('error'), __('errors'));
            return redirect()->back();
        endif;
    }

    public function details($id){
        $sale    = $this->repo->getFind($id);
        return view('sell::sale.details_modal',compact('sale'));
    }

        //product variation locatio details find
        public function VariationLocationFind(Request $request){
            $response =[];
            if($request->ajax()):
                $variationLocationDetails = $this->purchaseRepo->VariationLocationSkuFind($request);

                if($variationLocationDetails):
                    $request['variation_location_id'] = $variationLocationDetails->id;
                    $variation_location  = $this->repo->variationLocationItem($request);
                    $randomNumber        = Str::random(5);
                    $view =  view('sell::sale.variation_location_item',compact('variation_location','randomNumber'))->render();
                    return response()->json([
                        'single'=>true,
                        'id'    => $variation_location->id,
                        'view'  => $view
                    ]);
                else:
                    $vari_loc_finds = $this->repo->VariationLocationFind($request);
                    foreach ($vari_loc_finds as $item) {
                        $response[] = [
                            'value'   => $item->id,
                            'label'   => @$item->product->name.'-'.$item->ProductVariation->name.'-'.$item->ProductVariation->sub_sku,
                            'qty_available'=>$item->qty_available,
                        ];
                    }
                endif;
            endif;
            return response()->json($response);

        }

    public function VariationLocationItem(Request $request){
        $variation_location  = $this->repo->variationLocationItemFind($request);
        $randomNumber        = Str::random(5);
        return view('sell::sale.variation_location_item',compact('variation_location','randomNumber'));

    }

    public function getTaxrate(Request $request){
        if($request->ajax()):
          $tax     = $this->taxRateRepo->getFind($request->tax_id);
          if($tax):
            $rate = $tax->tax_rate;
          else:
            $rate = 0;
          endif;
          return $rate;
        endif;
    }


    //payment
    public function managePayment($id){
        $sale   = $this->repo->getFind($id);
        return view('sell::sale.payment.manage_payment',compact('sale'));
    }

    public function addPayment(AddPaymentRequest $request){
        if(env('DEMO')) {
            Toastr::error(__('store_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }

        $sale                          = $this->repo->getFind($request->sale_id);
        if($sale->DueAmount < $request->amount):
            Toastr::warning('Please check maximum payment amount.', __('warning'));
            return redirect()->back();
        endif;

        if($this->repo->addPayment($request)):
            Toastr::success(__('add_payment_successfully'), __('success'));
            return redirect()->route('sale.index');
        else:
            Toastr::error(__('error'), __('errors'));
            return redirect()->back();
        endif;
    }

    public function editPayment($id){
        $payment     = $this->repo->getFindPayment($id);
        return view('sell::sale.payment.edit_payment',compact('payment'));
    }
    public function updatePayment(AddPaymentRequest $request){
        if(env('DEMO')) {
            Toastr::error(__('update_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }
        $payment                           = $this->repo->getFindPayment($request->payment_id);
        $sale                               = $this->repo->getFind($payment->sale_id);
        $duePayment                        =  ($sale->payments->sum('amount') - $payment->amount);
        $payamount                         = $duePayment + $request->amount;
        if($sale->TotalSalePrice < $payamount):
            Toastr::warning('Please check maximum payment amount.', __('errors'));
            return redirect()->back();
        endif;
        if($this->repo->updatePayment($request)):
            Toastr::success(__('update_payment_successfully'), __('success'));
            return redirect()->route('sale.index');
        else:
            Toastr::error(__('error'), __('errors'));
            return redirect()->back();
        endif;
    }


    public function deletePayment($id){
        if(env('DEMO')) {
            Toastr::error(__('delete_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }
        if($this->repo->deletePayment($id)):
            Toastr::success(__('payment_deleted_successfully'), __('success'));
            return redirect()->back();
        else:
            Toastr::error(__('error'), __('errors'));
            return redirect()->back();
        endif;
    }

}
