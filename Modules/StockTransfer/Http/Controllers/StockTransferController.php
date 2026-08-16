<?php

namespace Modules\StockTransfer\Http\Controllers;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Branch\Repositories\BranchInterface;
use Modules\StockTransfer\Repositories\StockTransferInterface;
use Illuminate\Support\Str;
use Modules\Purchase\Repositories\PurchaseInterface;
use Modules\StockTransfer\Http\Requests\StoreRequest;
use Yajra\DataTables\DataTables;

class StockTransferController extends Controller
{
    protected $repo,$branchRepo,$purchaseRepo;
    public function __construct(StockTransferInterface $repo,BranchInterface $branchRepo,PurchaseInterface $purchaseRepo)
    {
        $this->repo         = $repo;
        $this->branchRepo   = $branchRepo;
        $this->purchaseRepo = $purchaseRepo;
    }
    public function index()
    {
       
        return view('stocktransfer::index');
    }

    public function getAllTransfer(){
        $stock_transfers  = $this->repo->getAllTransfer();
        return DataTables::of($stock_transfers)
        ->addIndexColumn() 
        ->editColumn('date',function($stock_transfer){
            return \Carbon\Carbon::parse($stock_transfer->created_at)->format('d-m-Y h:i:s');
        })
        ->editColumn('transfer_no',function($stock_transfer){
            return @$stock_transfer->transfer_no;
        })
        ->editColumn('from_branch',function($stock_transfer){
            return  @$stock_transfer->fromBranch->name;
        })
        ->editColumn('to_branch',function($stock_transfer){
            return @$stock_transfer->toBranch->name;
        })
        ->editColumn('total_amount',function($stock_transfer){
            return @businessCurrency($stock_transfer->business_id).' '. @$stock_transfer->total_amount;
        })
        ->editColumn('status',function($stock_transfer){
            return @$stock_transfer->my_status;
        })
        ->editColumn('transfered_by',function($stock_transfer){
            return  @$stock_transfer->user->name;
        })
        ->editColumn('status_update',function($stock_transfer){
            $statusUpdate = '';
            if($stock_transfer->status == \Modules\StockTransfer\Enums\StockTransferStatus::COMPLETED):
                $statusUpdate .= '<i class="fa-solid fa-ellipsis m-1"></i>';
            else:
                $statusUpdate .= '<div class="dropdown ">';
                $statusUpdate .= '<a href="#" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                $statusUpdate .= ' <i class="fa fa-ellipsis-vertical"></i> ';
                $statusUpdate .= '</a>'; 
                $statusUpdate .= '<div class="dropdown-menu todo-dropdown-menu" aria-labelledby="dropdownMenuButton"> ';
                $statusUpdate .= @$stock_transfer->my_status_update;
                $statusUpdate .= '</div>';
                $statusUpdate .= '</div>';
            endif;
            return $statusUpdate;
        })
        ->editColumn('action',function($stock_transfer){
            $action = '';
                
            if($stock_transfer->status == \Modules\StockTransfer\Enums\StockTransferStatus::COMPLETED):
                $action .= '<i class="fa fa-ellipsis"></i>'; 
            else:
                $action .= '<div class="dropdown">';
                $action .= '<a href="#" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                $action .= '<i class="fa fa-cogs"></i>';
                $action .= '</a>';
                $action .= '<div class="dropdown-menu todo-dropdown-menu" aria-labelledby="dropdownMenuButton"> 
                        <a href="#" class="dropdown-item modalBtn" data-url="'. route('stock.transfer.details',$stock_transfer->id) .'" data-title="'. __('stock_transfer_details') .'" data-bs-toggle="modal" data-bs-target="#dynamic-modal" data-modalsize="modal-xl"  ><i class="fa fa-eye"></i>'. __('view') .'</a>'; 
                    if(hasPermission('stock_transfer_update')):
                        $action .= '<a href="'.route('stock.transfer.edit',@$stock_transfer->id) .'" class="dropdown-item "   ><i class="fas fa-pen"></i>'. __('edit') .'</a>';
                    endif;
                    if(hasPermission('stock_transfer_delete')):
                        $action .= '<form action="'. route('stock.transfer.delete',@$stock_transfer->id) .'" method="post" class="delete-form" id="delete"  data-yes='. __('yes') .' data-cancel="'. __('cancel') .'" data-title="'. __('delete_stock_transfer') .'">';
                        $action .= method_field('delete');
                        $action .= csrf_field();
                        $action .= '<button type="submit" class="dropdown-item "  >';
                        $action .= '<i class="fas fa-trash-alt"></i>'. __('delete');
                        $action .= '</button>';
                        $action .= '</form>';
                    endif;
                    $action .= '</div>';
                    $action .= '</div>'; 
            endif;
            return $action;

        })  
        ->rawColumns(['date','transfer_no','from_branch','to_branch','total_amount','status','transfered_by','status_update', 'action'])
        ->make(true);
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
                $view =  view('stocktransfer::variation_location_item',compact('variation_location','randomNumber'))->render();
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
        return view('stocktransfer::variation_location_item',compact('variation_location','randomNumber'));
    }
 
    public function create()
    {  
        $branches      = $this->branchRepo->getBranches(business_id());
        return view('stocktransfer::create',compact('branches'));
    }

    public function store(StoreRequest $request)
    { 
        if($this->repo->stockCheck($request) == false):
            Toastr::error(__('out_of_stock'),__('errors'));
            return redirect()->back()->withInput($request->all());
        endif;
        if(env('DEMO')) {
            Toastr::error(__('store_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        } 
        if($this->repo->store($request)):
            Toastr::success(__('stock_transfered_successfully'), __('success'));
            return redirect()->route('stock.transfer.index');
        else:
            Toastr::error(__('error'), __('errors'));
            return redirect()->back();
        endif;
    }
 
    public function details($id)
    {
        $stockTransfer   = $this->repo->getFind($id);
        return view('stocktransfer::transfer_details_modal',compact('stockTransfer'));
    }
  
    public function edit($id)
    {
        $branches      = $this->branchRepo->getBranches(business_id());
        $stockTransfer   = $this->repo->getFind($id);
        return view('stocktransfer::edit',compact('branches','stockTransfer'));
    }

    
    public function update(Request $request)
    {
        if(env('DEMO')) {
            Toastr::error(__('store_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        } 
        if($this->repo->update($request->stock_t_id,$request)):
            Toastr::success(__('stock_transfer_updated_successfully'), __('success'));
            return redirect()->route('stock.transfer.index');
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
            Toastr::success(__('stock_transfer_deleted_successfully'), __('success'));
            return redirect()->route('stock.transfer.index');
        else:
            Toastr::error(__('error'), __('errors'));
            return redirect()->back();
        endif;
    } 

    public function StatusUpdate(Request $request,$id){
        if(env('DEMO')) {
            Toastr::error(__('status_update_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        } 
        if($this->repo->statusUpdate($id,$request)):
            Toastr::success(__('stock_transfer_updated_status_successfully'), __('success'));
            return redirect()->route('stock.transfer.index');
        else:
            Toastr::error(__('error'), __('errors'));
            return redirect()->back();
        endif;
    }
}
