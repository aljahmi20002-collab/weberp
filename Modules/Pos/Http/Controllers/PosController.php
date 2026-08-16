<?php

namespace Modules\Pos\Http\Controllers;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Brand\Repositories\BrandInterface;
use Modules\Category\Repositories\CategoryInterface;
use Modules\Pos\Repositories\PosInterface;
use Modules\Product\Repositories\ProductInterface;
use Illuminate\Support\Str;
use Modules\Branch\Repositories\BranchInterface;
use Modules\Customer\Enums\CustomerType;
use Modules\Customer\Repositories\CustomerInterface;
use Modules\Pos\Entities\Pos;
use Modules\Pos\Http\Requests\AddPaymentRequest;
use Modules\Pos\Http\Requests\StoreRequest;
use Modules\TaxRate\Repositories\TaxRateInterface;
use Yajra\DataTables\DataTables;

class PosController extends Controller
{
    protected $productRepo,$categoryRepo,$brandRepo,$repo,$customerRepo,$branchRepo,$taxRateRepo;
    public function __construct(
        ProductInterface $productRepo,
        CategoryInterface $categoryRepo,
        BrandInterface $brandRepo,
        PosInterface $repo,
        CustomerInterface $customerRepo,
        BranchInterface $branchRepo,
        TaxRateInterface $taxRateRepo
        )
    {
        $this->productRepo    = $productRepo;
        $this->categoryRepo   = $categoryRepo;
        $this->brandRepo      = $brandRepo;
        $this->repo           = $repo;
        $this->customerRepo   = $customerRepo;
        $this->branchRepo     = $branchRepo;
        $this->taxRateRepo   = $taxRateRepo;
    }

    public function index(Request $request)
    {
        $branches      = $this->branchRepo->getBranches(business_id());
        $categories    = $this->categoryRepo->get();
        $brands        = $this->brandRepo->get();
        $customers     = $this->customerRepo->getActiveCustomers(business_id());
        $taxRates      = $this->taxRateRepo->getActive(business_id());
        $posPage       = 'true';
        return view('pos::index',compact('categories', 'brands','customers','branches','taxRates','posPage'));
    }


    public function print($id){
        $pos     = $this->repo->getFind($id);
        return view('pos::print',compact('pos'));
    }

    public function mobilePrint($id){
         
        $pos     = $this->repo->getFind($id);
        return view('pos::mobile_pos_print',compact('pos'));
    }

    public function products(Request $request){ 
        if($request->ajax()):
         
            $products = $this->productRepo->getProducts($request); 
            if($products->count() > 0):
                $randomNumber        = Str::random(5);
                 return  view('pos::product_list',compact('products','randomNumber'));
            else:
                return '';
            endif;
        endif;  
       return ''; 
    } 

    public function FilterProduct(Request $request){ 
        if($request->ajax()):
            $products = $this->productRepo->getProducts($request);  
            if($products->count() > 0):
                $randomNumber        = Str::random(5);
                 return  view('pos::product_list',compact('products','randomNumber'));
            else:
                return '';
            endif;
        endif;  
       return '';  
    } 


    
    //product variation location details find 
    public function VariationLocationFind(Request $request){
        $response =[];
        if($request->ajax()):  
            $variationLocationDetails = $this->repo->VariationLocationSkuFind($request);
            if($variationLocationDetails): 
                $request['variation_location_id'] = $variationLocationDetails->id;
                $variation_location  =  $this->repo->variationLocationItemFind($request->variation_location_id);
                $randomNumber        =  Str::random(5);
                $view                =  view('pos::variation_location_item',compact('variation_location','randomNumber'))->render();
                return response()->json([
                    'single'=>true,
                    'id'    => $variation_location->id,
                    'view'  => $view
                ]);
            else: 
                $vari_loc_finds = $this->repo->VariationLocationSearchFind($request); 
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
     
    public function VariationLocationItemGet(Request $request){
        $variation_location  = $this->repo->variationLocationItemFindGet($request->variation_location_id);  
        $randomNumber        = Str::random(5);
        return view('pos::.variation_location_item',compact('variation_location','randomNumber')); 
    }
 
    public function VariationLocationItem(Request $request){
        $variation_location  = $this->repo->variationLocationItemFind($request->id); 
        if($variation_location->qty_available<=0): 
            return '';
        endif;
        $randomNumber        = Str::random(5);
        return view('pos::variation_location_item',compact('variation_location','randomNumber')); 
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

    public function list(){ 
        return view('pos::pos_index');
    }
 
    public function getAllPos(){

        ini_set('memory_limit', '50012M');
        ini_set('max_execution_time', 90000000); //900 seconds 
        
        $poses  = $this->repo->getAllPos(); 

        return DataTables::of($poses)
        ->addIndexColumn() 
        ->editColumn('date',function($pos){
            return \Carbon\Carbon::parse($pos->created_at)->format('d-m-Y h:i:s');
        })
        ->editColumn('invoice_no',function($pos){
            return @$pos->invoice_no;
        })
        ->editColumn('branch',function($pos){
            return  @$pos->branch->name;
        })
        ->editColumn('customer_details',function($pos){
            $customerDetails  = '';
            $customerDetails  .= '<div class="d-flex">'; 
            $customerDetails  .= '<span>'. __('type').'</span>    :'. __(\Config::get('pos_default.customer_type.'.@$pos->customer_type));
            $customerDetails  .='</div>';
            if($pos->customer_type == \Modules\Customer\Enums\CustomerType::EXISTING_CUSTOMER):
                $customerDetails  .=' <div class="d-flex"> ';
                $customerDetails  .= '<span>'.__('name') .'</span>    :'. @$pos->customer->name;
                $customerDetails  .= '</div>';
                $customerDetails  .= '<div class="d-flex">';
                $customerDetails  .= '<span>'.__('email').'</span> :'. @$pos->customer->email;
                $customerDetails  .= '</div>';
                $customerDetails  .= '<div class="d-flex">';
                $customerDetails  .= '<span>'.__('phone').'</span>  :'. @$pos->customer->phone;
                $customerDetails  .= '</div>';
                $customerDetails  .= '<div class="d-flex">';
                $customerDetails  .= '<span>'.__('address').'</span>  : <span class="address"> '.@$pos->customer->address.'</span>';
                $customerDetails  .= '</div>'; 
            elseif($pos->customer_type == CustomerType::WALK_CUSTOMER):
                $customerDetails  .= '<div class="d-flex">';
                $customerDetails  .= '<span>'.__('phone').'</span>  : <span class="address"> '.@$pos->customer_phone.'</span>';
                $customerDetails  .= '</div>';  
            endif;

            return $customerDetails;
        })
        ->editColumn('payment_status',function($pos){
            return @$pos->my_payment_status;
        })
        ->editColumn('shipping_status',function($pos){
            return  __(\Config::get('pos_default.shpping_status.'.$pos->shipping_status));
        })
        ->editColumn('total_sell_price',function($pos){
            return  @businessCurrency($pos->business_id) .' '.@number_format($pos->total_sale_price,2);
        })
        ->editColumn('created_by',function($pos){
            return  @$pos->user->name;
        })
        ->editColumn('action',function($pos){
            $action = '';
            $action .= '<div class="dropdown">';
            $action .=  '<a href="#" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
            $action .= '<i class="fa fa-cogs"></i>';
            $action .= '</a>';
            $action .= '<div class="dropdown-menu todo-dropdown-menu" aria-labelledby="dropdownMenuButton">';
            $action .= '<a   class="dropdown-item "  href="'.route('pos.print',$pos->id) .'" target="_blank"><i class="fa fa-print"></i>'.__('print').'</a>';  
            $action .=  '<a   class="dropdown-item "  href="'.route('pos.invoice.view',$pos->id).'"  ><i class="fa fa-file-invoice-dollar"></i>'. __('invoice').'</a> '; 
            $action .= '<a href="#" class="dropdown-item modalBtn" data-url="'. route('pos.details',$pos->id) .'" data-title="'. __('pos_details') .'" data-bs-toggle="modal" data-bs-target="#dynamic-modal" data-modalsize="modal-xl"   ><i class="fa fa-eye"></i>'. __('view') .'</a> ';
                if(hasPermission('pos_read_payment')):
                    $action .= '<a href="#" data-title="'.__('manage_pos_payment').'" class="dropdown-item modalBtn" data-modalsize="modal-xl" data-bs-toggle="modal" data-bs-target="#dynamic-modal"  data-url="'.route('pos.manage.payment',$pos->id) .'"><i class="fa fa-hand-holding-dollar"></i>'. __('payment') .'</a>';  
                endif;
                
                if(hasPermission('pos_update')):
                    $action .= '<a href="'. route('pos.edit',@$pos->id) .'" class="dropdown-item "  ><i class="fas fa-pen"></i>'. __('edit') .'</a>';
                endif;
                if(hasPermission('pos_delete')):
                    $action .= '<form action="'. route('pos.delete',@$pos->id) .'" method="post" class="delete-form" id="delete"  data-yes='. __('yes') .' data-cancel="'. __('cancel') .'" data-title="'. __('delete_pos') .'">';
                    $action .= method_field('delete');
                    $action .= csrf_field();
                    $action .= '<button type="submit" class="dropdown-item"   >';
                    $action .= '<i class="fas fa-trash-alt"></i>'. __('delete');
                    $action .= '</button>';
                    $action .= '</form>';
                endif;
                $action .= '</div>';
                $action .= '</div>';

                return $action;

        })  
        ->rawColumns([ 'date','invoice_no','branch','customer_details','payment_status','shipping_status','total_sell_price', 'created_by','action' ])
        ->make(true);
    }


    public function create()
    {
        return view('pos::create');
    }
 
    public function store(StoreRequest $request)
    {
       
        if(env('DEMO')) {
            Toastr::error(__('store_system_is_disable_for_the_demo_mode'), __('errors'));
            return redirect()->back()->withInput();
        } 
        if($pos=$this->repo->store($request)):
            return redirect()->route('pos.index')->with('pos',$pos);
        else:
            Toastr::error(__('error'), __('errors'));
            return redirect()->back();
        endif;
    }

    public function details($id)
    {
        $pos  = $this->repo->getFind($id);
        return view('pos::details_modal',compact('pos'));
    }
 
    public function edit($id)
    {
        $editPos   = $this->repo->getFind($id);
        $branches      = $this->branchRepo->getBranches(business_id());
        $categories    = $this->categoryRepo->get();
        $brands        = $this->brandRepo->get();
        $customers     = $this->customerRepo->getActiveCustomers(business_id());
        $taxRates      = $this->taxRateRepo->getActive(business_id());
        return view('pos::pos_edit.edit',compact('editPos','categories', 'brands','customers','branches','taxRates'));
    }
 
    public function update(StoreRequest $request)
    { 
        if(env('DEMO')) {
            Toastr::error(__('delete_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        } 
        if($this->repo->update($request->id,$request)):
            Toastr::success(__('pos_sale_updated_successfully'), __('success')); 
            return redirect()->route('pos.list');
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
            Toastr::success(__('pos_sale_delete_successfully'), __('success')); 
            return redirect()->back();
        else:
            Toastr::error(__('error'), __('errors'));
            return redirect()->back();
        endif;
    }

 
    //payment 
    public function managePayment($id){
        $pos   = $this->repo->getFind($id);
        return view('pos::payment.manage_payment',compact('pos'));
    }

    public function addPayment(AddPaymentRequest $request){
        if(env('DEMO')) {
            Toastr::error(__('store_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }
 
        $pos                          = $this->repo->getFind($request->pos_id);
        if($pos->DueAmount < $request->amount):
            Toastr::warning('Please check maximum payment amount.', __('warning'));
            return redirect()->back(); 
        endif;

        if($this->repo->addPayment($request)):
            Toastr::success(__('add_payment_successfully'), __('success'));
            return redirect()->route('pos.list');
        else:
            Toastr::error(__('error'), __('errors'));
            return redirect()->back();
        endif;
    }

    public function editPayment($id){ 
        $payment     = $this->repo->getFindPayment($id);
        return view('pos::payment.edit_payment',compact('payment'));
    }
    public function updatePayment(AddPaymentRequest $request){
        if(env('DEMO')) {
            Toastr::error(__('update_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        } 
        $payment                           = $this->repo->getFindPayment($request->payment_id);
        $pos                               = $this->repo->getFind($payment->pos_id);
        $duePayment                        =  ($pos->payments->sum('amount') - $payment->amount);
        $payamount                         = $duePayment + $request->amount;  
        if($pos->TotalSalePrice < $payamount):
            Toastr::warning('Please check maximum payment amount.', __('warning'));
            return redirect()->back(); 
        endif; 
        if($this->repo->updatePayment($request)):
            Toastr::success(__('update_payment_successfully'), __('success'));
            return redirect()->route('pos.list');
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
