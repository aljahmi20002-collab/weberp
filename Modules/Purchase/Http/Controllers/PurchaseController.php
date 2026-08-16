<?php

namespace Modules\Purchase\Http\Controllers;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Branch\Repositories\BranchInterface;
use Modules\Purchase\Repositories\PurchaseInterface;
use Modules\Supplier\Repositories\SupplierInterface;
use Modules\TaxRate\Repositories\TaxRateInterface;
use Illuminate\Support\Str;
use Modules\Purchase\Enums\PurchaseStatus;
use Modules\Purchase\Http\Requests\AddPaymentRequest;
use Modules\Purchase\Http\Requests\StoreRequest;
use Yajra\DataTables\DataTables;

class PurchaseController extends Controller
{
    protected $repo,$taxRepo,$supplierRepo,$branchRepo;
    public function __construct(
        PurchaseInterface $repo,
        TaxRateInterface $taxRepo,
        SupplierInterface $supplierRepo,
        BranchInterface $branchRepo
        )
    {
        $this->repo         = $repo;
        $this->taxRepo      = $taxRepo;
        $this->supplierRepo = $supplierRepo; 
        $this->branchRepo   = $branchRepo;    
    }
    public function index()
    { 
        return view('purchase::index');
    }

    public function getAllPurchase(){

        ini_set('memory_limit', '50012M');
        ini_set('max_execution_time', 90000000); //900 seconds 
        
        $purchases   = $this->repo->getAllPurchase(); 
        return DataTables::of($purchases)
        ->addIndexColumn()  
        ->editColumn('date',function($purchase){
            return \Carbon\Carbon::parse($purchase->created_at)->format('d-m-Y h:i:s');
        }) 
        ->editColumn('purchase_no',function($purchase){
            return @$purchase->purchase_no;
        }) 
        ->editColumn('branch',function($purchase){
            $branches = '';
            foreach ($purchase->PurchasedBranch as $branch){
                $branches .= '<span class="badge badge-pill  badge-primary">'.@$branch->name .'</span>';
            }
            return $branches; 
        }) 
        ->editColumn('supplier',function($purchase){
                $supplier   = '';
                $supplier  .= '<div class="d-flex">';
                $supplier  .= '<span>'. __('name').'</span>    :'. @$purchase->supplier->name;
                $supplier  .= '</div>';
                $supplier  .= '<div class="d-flex">';
                $supplier  .= '<span>'. __('company') .'</span> :'.@$purchase->supplier->company_name;
                $supplier  .= '</div>';
                $supplier  .= '<div class="d-flex">';
                $supplier  .= '<span>'.__('phone').'</span>  :'. @$purchase->supplier->phone;
                $supplier  .= '</div>'; 
                $supplier  .= '<div class="d-flex">';
                $supplier  .= '<span>'. __('address') .'</span>  : <span class="address">'.@$purchase->supplier->address .'</span>';
                $supplier  .='</div>'; 
                return $supplier;
        }) 
        ->editColumn('purchase_status',function($purchase){
            return @$purchase->my_purchase_status;
        }) 
        ->editColumn('payment_status',function($purchase){
            return @$purchase->my_payment_status;
        }) 
        ->editColumn('total_purchase_cost',function($purchase){
            return  @businessCurrency($purchase->business_id).' '. @number_format($purchase->total_purchase_cost,2);
        })
        ->editColumn('received_by',function($purchase){
            return @$purchase->user->name;
        })
        ->editColumn('action',function($purchase){
            $action = '';
            
            $action .='<div class="dropdown">';
        

            $action .= '<a href="#" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
            $action .= '<i class="fa fa-cogs"></i>';
            $action .= '</a>';
            $action .= '<div class="dropdown-menu todo-dropdown-menu" aria-labelledby="dropdownMenuButton"> '; 
            if(hasPermission('purchase_status_update')):
                if($purchase->purchase_status     == PurchaseStatus::PENDING):
                        $action .= '<form action="'.route('purchase.status.update',[$purchase->id,PurchaseStatus::ORDERED]).'" method="post">';
                        $action .= method_field('put');
                        $action .= csrf_field();
                        $action .= '<button type="submit"  class="dropdown-item" > <i class="fa fa-cart-shopping"></i>'.__('ordered').'</button>';   
                        $action .= '</form>';
                endif;
                if($purchase->purchase_status == PurchaseStatus::ORDERED || $purchase->purchase_status     == PurchaseStatus::PENDING):
                    $action .= '<form action="'.route('purchase.status.update',[$purchase->id,PurchaseStatus::RECEIVED]).'" method="post">';
                    $action .= method_field('put');
                    $action .= csrf_field();
                    $action .= '<button type="submit"  class="dropdown-item" > <i class="fa fa-hand-holding-dollar"></i>'.__('received').'</button>';  
                    $action .= '</form>';
                endif;
            endif;

            $action .= '<a href="'.route('purchase.invoice.view',$purchase->id) .'"  class="dropdown-item" ><i class="fa fa-print"></i>'. __('print').'</a>  ';
            $action .= '<a href="#" class="dropdown-item modalBtn" data-url="'. route('purchase.details',$purchase->id) .'" data-title="'. __('purchase_details') .'" data-bs-toggle="modal" data-bs-target="#dynamic-modal" data-modalsize="modal-xl"   ><i class="fa fa-eye"></i>'.__('view') .'</a> ';
        
                if(hasPermission('purchase_read_payment')):
                    $action .= '<a href="#" data-title="'. __('manage_payment') .'" class="dropdown-item modalBtn" data-modalsize="modal-xl" data-bs-toggle="modal" data-bs-target="#dynamic-modal"  data-url="'. route('purchase.manage.payment',$purchase->id) .'"> <i class="fa fa-hand-holding-dollar"></i>'. __('payment') .'</a>';  
                endif;
                if(hasPermission('purchase_update')):
                    $action .= '<a href="'. route('purchase.edit',@$purchase->id) .'" class="dropdown-item"  ><i class="fas fa-pen"></i>'. __('edit') .'</a>';
                endif;
                if(hasPermission('purchase_delete')):
                    $action .= '<form action="'.route('purchase.delete',@$purchase->id) .'" method="post" class="delete-form" id="delete"  data-yes='. __('yes') .' data-cancel="'.__('cancel') .'" data-title="'. __('delete_purchase') .'">';
                    $action .= method_field('delete');
                    $action .= csrf_field();
                    $action .= '<button type="submit" class="dropdown-item " >';
                    $action .= '<i class="fas fa-trash-alt"></i>'. __('delete');
                    $action .= '</button>';
                    $action .= '</form>';
                endif;
            $action .='</div>';
            $action .= '</div>'; 
            return $action;
        })
        ->rawColumns(['date','purchase_no','branch','supplier','purchase_status','payment_status','total_purchase_cost', 'received_by', 'action'])
        ->make(true);
    }
    public function create()
    { 
        $suppliers     = $this->supplierRepo->getActive();
        $branches      = $this->branchRepo->getBranches(business_id());
        return view('purchase::create',compact('suppliers','branches'));
    }

 
    public function store(StoreRequest $request)
    { 
        if(env('DEMO')) {
            Toastr::error(__('store_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }
    
        if($this->repo->store($request)):
            Toastr::success(__('purchase_store_successfully'), __('success'));
            return redirect()->route('purchase.index');
        else:
            Toastr::error(__('error'), __('errors'));
            return redirect()->back();
        endif;
    }
 
    public function view($id)
    {
        return view('purchase::show');
    }

    public function edit($id)
    {
        $suppliers     = $this->supplierRepo->getActive();
        $branches      = $this->branchRepo->getBranches(business_id());
        $purchase      = $this->repo->getFind($id);
        return view('purchase::edit',compact('suppliers', 'branches','purchase'));
    }
 
    public function update(StoreRequest $request)
    {
        if(env('DEMO')) {
            Toastr::error(__('update_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }
        if($this->repo->update($request->purchase_id,$request)):
            Toastr::success(__('purchase_update_successfully'), __('success'));
            return redirect()->route('purchase.index');
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
            Toastr::success(__('purchase_deleted_successfully'), __('success'));
            return redirect()->route('purchase.index');
        else:
            Toastr::error(__('error'), __('errors'));
            return redirect()->back();
        endif;
    }

   
    //product variation locatio details find 
    public function VariationLocationFind(Request $request){
        $response =[];
        if($request->ajax()): 
            $variationLocationDetails = $this->repo->VariationLocationSkuFind($request);
 
            if($variationLocationDetails):
                $request['variation_location_id'] = $variationLocationDetails->id;
                $variation_location  = $this->repo->variationLocationItem($request);
                $randomNumber        = Str::random(5);
                $view =  view('purchase::variation_location_item',compact('variation_location','randomNumber'))->render();
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
        
        $variation_location  = $this->repo->variationLocationItem($request);
        $randomNumber        = Str::random(5);
        return view('purchase::variation_location_item',compact('variation_location','randomNumber'));
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

    public function statusUpdate($id,$status){
        if(env('DEMO')) {
            Toastr::error(__('store_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }
 
        if($this->repo->statusUpdate($id,$status)):
            Toastr::success(__('status_updated_successfully'), __('success'));
            return redirect()->back();
        else:
            Toastr::error(__('error'), __('errors'));
            return redirect()->back();
        endif;
    }

    public function details($id){
        $purchase   = $this->repo->getFind($id);
        return view('purchase::details_modal',compact('purchase'));
    }

    public function managePayment($id){
        $purchase   = $this->repo->getFind($id);
        return view('purchase::manage_payment',compact('purchase'));
    }

    public function addPayment(AddPaymentRequest $request){
        if(env('DEMO')) {
            Toastr::error(__('store_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }
 
        $purchase                          = $this->repo->getFind($request->purchase_id);
        if($purchase->DueAmount < $request->amount):
            Toastr::warning('Please check maximum payment amount.', __('warning'));
            return redirect()->back(); 
        endif;

        if($this->repo->addPayment($request)):
            Toastr::success(__('add_payment_successfully'), __('success'));
            return redirect()->route('purchase.index');
        else:
            Toastr::error(__('error'), __('errors'));
            return redirect()->back();
        endif;
    }

    public function editPayment($id){ 
        $payment     = $this->repo->getFindPayment($id);
        return view('purchase::edit_payment',compact('payment'));
    }
    public function updatePayment(AddPaymentRequest $request){
        if(env('DEMO')) {
            Toastr::error(__('update_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        } 
        $payment                           = $this->repo->getFindPayment($request->payment_id);
        $purchase                          = $this->repo->getFind($payment->purchase_id);
        $duePayment                        =  ($purchase->payments->sum('amount') - $payment->amount);
        $payamount                         = $duePayment + $request->amount;  
        if($purchase->TotalPurchaseCost < $payamount):
            Toastr::warning('Please check maximum payment amount.', __('warning'));
            return redirect()->back(); 
        endif; 
        if($this->repo->updatePayment($request)):
            Toastr::success(__('update_payment_successfully'), __('success'));
            return redirect()->route('purchase.index');
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
    public function invoiceView($purchase_id){
        $purchase = $this->repo->getFind($purchase_id);
        return view('purchase::invoice_view',compact('purchase'));
    }
 
}
