<?php

namespace Modules\Purchase\Http\Controllers;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Branch\Repositories\BranchInterface;
use Modules\Purchase\Http\Requests\PurchaseReturn\StoreRequest;
use Modules\Purchase\Repositories\PurchaseReturn\PurchaseReturnInterface;
use Modules\Supplier\Repositories\SupplierInterface;
use Modules\TaxRate\Repositories\TaxRateInterface;
use Illuminate\Support\Str;
use Modules\Purchase\Http\Requests\PurchaseReturn\AddPaymentRequest;
use Modules\Purchase\Repositories\PurchaseInterface;
use Yajra\DataTables\DataTables;

class PurchaseReturnController extends Controller
{
    protected $repo,$taxRepo,$supplierRepo,$branchRepo,$purchaseRepo;
    public function __construct(
        PurchaseReturnInterface $repo,
        TaxRateInterface $taxRepo,
        SupplierInterface $supplierRepo,
        BranchInterface $branchRepo,
        PurchaseInterface $purchaseRepo
        )
    {
        $this->repo         = $repo;
        $this->taxRepo      = $taxRepo;
        $this->supplierRepo = $supplierRepo; 
        $this->branchRepo   = $branchRepo; 
        $this->purchaseRepo = $purchaseRepo;   
    }
    public function index()
    {
        
        return view('purchase::purchase-return.index');
    }

    public function getAllPurchaseReturn(){
        ini_set('memory_limit', '50012M');
        ini_set('max_execution_time', 90000000); //900 seconds 
        
        $purchases_return   = $this->repo->getAllPurchaseReturn();  
        return DataTables::of($purchases_return)
        ->addIndexColumn() 
        ->editColumn('date',function($purchase_return){
            return \Carbon\Carbon::parse($purchase_return->created_at)->format('d-m-Y h:i:s');
        })
        ->editColumn('return_no',function($purchase_return){
            return  @$purchase_return->return_no;
        })
        ->editColumn('branch',function($purchase_return){
            $branches ='';
            foreach ($purchase_return->PurchasedReturnBranch as $branch){
                $branches  .= '<span class="badge badge-pill  badge-primary">'.@$branch->name.'</span> ';
            }
            return $branches;
        })
        ->editColumn('supplier',function($purchase_return){
            $supplier   = '';
            $supplier  .= '<div class="d-flex">';
            $supplier  .= '<span>'. __('name').'</span>    :'.@$purchase_return->supplier->name;
            $supplier  .= '</div>';
            $supplier  .= '<div class="d-flex">';
            $supplier  .= '<span>'.__('company') .'</span> :'.@$purchase_return->supplier->company_name;
            $supplier  .= '</div>';
            $supplier  .= '<div class="d-flex">';
            $supplier  .= '<span>'.__('phone').'</span>  :'.@$purchase_return->supplier->phone;
            $supplier  .= '</div>'; 
            $supplier  .= '<div class="d-flex">';
            $supplier  .= '<span>'. __('phone') .'</span>  : <span class="address">'. @$purchase_return->supplier->address;
            $supplier  .= '</span>';
            $supplier  .= '</div>';
            return $supplier;
        }) 
        ->editColumn('payment_status',function($purchase_return){
            return  @$purchase_return->my_payment_status;
        })
        ->editColumn('total_return_price',function($purchase_return){
            return @businessCurrency($purchase_return->business_id) .' '.@number_format($purchase_return->total_purchase_return_price,2);
        })
        ->editColumn('returned_by',function($purchase_return){
            return @$purchase_return->user->name;
        })
        ->editColumn('action',function($purchase_return){
            $action = '';
            $action .= '<div class="dropdown">';
            $action .= '<a href="#" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
            $action .= '<i class="fa fa-cogs"></i>';
            $action .= '</a>';
            $action .= '<div class="dropdown-menu todo-dropdown-menu" aria-labelledby="dropdownMenuButton">';
            $action .= '<a href="'. route('purchase.return.invoice.view',$purchase_return->id) .'"   class="dropdown-item " ><i class="fa fa-print"></i>'. __('print') .'</a> '; 
            $action .= '<a href="#" class="dropdown-item modalBtn" data-url="'.route('purchase.return.details',$purchase_return->id) .'" data-title="'.__('purchase_return_details') .'" data-bs-toggle="modal" data-bs-target="#dynamic-modal" data-modalsize="modal-xl"  ><i class="fa fa-eye"></i>'. __('view') .'</a> ';
                if(hasPermission('purchase_return_read_payment')):
                    $action .= '<a href="#" data-title="'.__('manage_return_payment') .'" class="dropdown-item modalBtn" data-modalsize="modal-xl" data-bs-toggle="modal" data-bs-target="#dynamic-modal"  data-url="'. route('purchase.return.manage.payment',$purchase_return->id) .'"><i class="fa fa-hand-holding-dollar"></i>'.__('payment') .'</a>';
                endif;
                if(hasPermission('purchase_return_update')):
                    $action .= '<a href="'.route('purchase.return.edit',@$purchase_return->id) .'" class="dropdown-item" ><i class="fas fa-pen"></i>'.__('edit') .'</a>';
                endif;
                if(hasPermission('purchase_return_delete')):
                    $action .= '<form action="'.route('purchase.return.delete',@$purchase_return->id) .'" method="post" class="delete-form" id="delete"  data-yes='. __('yes') .' data-cancel="'. __('cancel') .'" data-title="'. __('delete_purchase_return') .'">';
                    $action .= method_field('delete');
                    $action .= csrf_field();
                    $action .= '<button type="submit" class="dropdown-item"  >';
                    $action .= '<i class="fas fa-trash-alt"></i>'. __('delete');
                    $action .= '</button>';
                    $action .= '</form>';
                endif; 
            $action .= '</div>';
            $action .= '</div>';

            return $action;
        })

        ->rawColumns(['date','return_no','branch','supplier','purchase_status','payment_status','total_return_price','returned_by','action'])
        ->make(true);
    }

    public function create()
    { 
        $suppliers     = $this->supplierRepo->getActive();
        $branches      = $this->branchRepo->getBranches(business_id());
        return view('purchase::purchase-return.create',compact('suppliers','branches'));
    }
 
    public function store(StoreRequest $request)
    { 
        if(env('DEMO')) {
            Toastr::error(__('store_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }
    
        if($this->repo->store($request)):
            Toastr::success(__('purchase_return_store_successfully'), __('success'));
            return redirect()->route('purchase.return.index');
        else:
            Toastr::error(__('error'), __('errors'));
            return redirect()->back();
        endif;
    }
 

    public function edit($id)
    {
        $suppliers            = $this->supplierRepo->getActive();
        $branches             = $this->branchRepo->getBranches(business_id());
        $purchase_return      = $this->repo->getFind($id);
        return view('purchase::purchase-return.edit',compact('suppliers', 'branches','purchase_return'));
    }
 
    public function update(StoreRequest $request)
    {
        if(env('DEMO')) {
            Toastr::error(__('update_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }
        if($this->repo->update($request->purchase_return_id,$request)):
            Toastr::success(__('purchase_return_update_successfully'), __('success'));
            return redirect()->route('purchase.return.index');
        else:
            Toastr::error(__('error'), __('errors'));
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
            Toastr::success(__('purchase_return_deleted_successfully'), __('success'));
            return redirect()->route('purchase.return.index');
        else:
            Toastr::error(__('error'), __('errors'));
            return redirect()->back();
        endif;
    }

        //product variation locatio details find 
        public function VariationLocationFind(Request $request){
            $response =[];
            if($request->ajax()): 
                $variationLocationDetails = $this->purchaseRepo->VariationLocationSkuFind($request);
                if($variationLocationDetails && $variationLocationDetails->qty_available <= 0):
                    $response[] =[
                        'value'   => $variationLocationDetails->id,
                        'label'   => @$variationLocationDetails->product->name.'-'.$variationLocationDetails->ProductVariation->name.'-'.$variationLocationDetails->ProductVariation->sub_sku,
                        'qty_available'=>$variationLocationDetails->qty_available,
                    ];
                elseif($variationLocationDetails && $variationLocationDetails->qty_available > 0):
                    $request['variation_location_id'] = $variationLocationDetails->id;
                    $variation_location  = $this->repo->variationLocationItem($request);
                    $randomNumber        = Str::random(5);
                    $view =  view('purchase::purchase-return.variation_location_item',compact('variation_location','randomNumber'))->render();
                    return response()->json([
                        'single'=>true,
                        'id'    => $variation_location->id,
                        'view'  => $view
                    ]);
                else: 
                    $vari_loc_finds = $this->repo->VariationLocationFind($request);
                    foreach ($vari_loc_finds as $item) {
                        $response[] =[
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
        $variation_location  = $this->repo->variationLocationItem($request);
        $randomNumber        = Str::random(5);
        return view('purchase::purchase-return.variation_location_item',compact('variation_location','randomNumber'));
    }

    public function getTaxrate(Request $request){
        if($request->ajax()):
          $tax     = $this->taxRepo->getFind($request->tax_id);
          if($tax):
            $rate = $tax->tax_rate;
          else:
            $rate = 0;
          endif;
          return $rate;
        endif;
    }

    public function details($id){
        $purchase_return   = $this->repo->getFind($id);
        return view('purchase::purchase-return.return_details_modal',compact('purchase_return'));
    }

    public function managePayment($id){
        $purchase_return   = $this->repo->getFind($id);
        return view('purchase::purchase-return.manage_payment',compact('purchase_return'));
    }

    public function addPayment(AddPaymentRequest $request){
        if(env('DEMO')) {
            Toastr::error(__('store_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }
 
        $purchase                          = $this->repo->getFind($request->purchase_return_id);
        if($purchase->DueAmount < $request->amount):
            Toastr::warning('Please check maximum payment amount.', __('warning'));
            return redirect()->back(); 
        endif;

        if($this->repo->addPayment($request)):
            Toastr::success(__('add_payment_successfully'), __('success'));
            return redirect()->route('purchase.return.index');
        else:
            Toastr::error(__('error'), __('errors'));
            return redirect()->back();
        endif;
    }

    public function editPayment($id){ 
        $payment     = $this->repo->getFindPayment($id);
        return view('purchase::purchase-return.edit_payment',compact('payment'));
    }
    public function updatePayment(AddPaymentRequest $request){
        if(env('DEMO')) {
            Toastr::error(__('update_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        } 
        $payment                           = $this->repo->getFindPayment($request->payment_id);
        $purchase_return                   = $this->repo->getFind($payment->purchase_return_id);
        $duePayment                        =  ($purchase_return->payments->sum('amount') - $payment->amount);
        $payamount                         = $duePayment + $request->amount;  
        if($purchase_return->TotalPurchaseReturnPrice < $payamount):
            Toastr::warning('Please check maximum payment amount.', __('warning'));
            return redirect()->back(); 
        endif; 
        if($this->repo->updatePayment($request)):
            Toastr::success(__('update_payment_successfully'), __('success'));
            return redirect()->route('purchase.return.index');
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

    public function invoiceView($purchase_return_id){
        $purchaseReturn = $this->repo->getFind($purchase_return_id);
        return view('purchase::purchase-return.return_invoice_view',compact('purchaseReturn'));
    }

}
