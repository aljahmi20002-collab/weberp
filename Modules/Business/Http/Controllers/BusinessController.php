<?php

namespace Modules\Business\Http\Controllers;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Business\Http\Requests\CreateRequest;
use Modules\Business\Http\Requests\StoreRequest;
use Modules\Business\Repositories\BusinessInterface;
use Yajra\DataTables\DataTables;

class BusinessController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    protected $businessRepo;
    public function __construct(BusinessInterface $businessRepo)
    {
        $this->businessRepo = $businessRepo;
    }
    public function index()
    {
        return view('business::index');
    }
    
    public function getAllBusiness(){
        $businesses    = $this->businessRepo->getAllBusinesses();
        return DataTables::of($businesses)
        ->addIndexColumn() 
        ->editColumn('business_name',function($business){
            return @$business->business_name;
        })
        ->editColumn('logo',function($business){
            return '<img src="'. $business->logo_img .'" width="50px"/>';
        })
        ->editColumn('start_date',function($business){
            return @dateFormat($business->start_date);
        })
        ->editColumn('currency',function($business){
            return @$business->currency->currency.' ('. @$business->currency->symbol .' )';
        })
        ->editColumn('sku_prefix',function($business){
            return @$business->sku_prefix;
        })
        ->editColumn('default_profit_percent',function($business){
            return  @$business->default_profit_percent;
        })
        ->editColumn('owner',function($business){
            return @$business->user->name .'<br/>'.@$business->user->email;
        })
        ->editColumn('status',function($business){
            return @$business->my_status;
        })
        ->editColumn('action',function($business){
            $action  = ''; 
                if(hasPermission('business_update') || hasPermission('business_delete') || hasPermission('business_branch_read')): 
                    $action .=  '<div class="dropdown">';
                    $action .= '<a href="#" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                    $action .= '<i class="fa fa-cogs"></i>';
                    $action .= '</a>';
                    $action .= '<div class="dropdown-menu todo-dropdown-menu" aria-labelledby="dropdownMenuButton">';
                        if(hasPermission('business_status_update')):
                            $action .= '<a class="dropdown-item" href="'. route('business.status.update',$business->id) .'">';
                            $action .= $business->status == \App\Enums\Status::ACTIVE? '<i class="fa fa-ban"></i>':'<i class="fa fa-check"></i>';
                            $action .=  @statusUpdate($business->status);
                            $action .='</a>';
                        endif;
                        if(hasPermission('business_update')):
                            $action .= '<a href="'. route('business.edit',@$business->id) .'" class="dropdown-item"  ><i class="fa fa-pen"></i> '. __('edit') .'</a>';
                        endif;
                        if(hasPermission('business_delete')):
                            $action .= '<form action="'.route('business.delete',@$business->id).'" method="post" class="delete-form" id="delete" data-yes='. __('yes') .' data-cancel="'. __('cancel') .'" data-title="'. __('delete_business') .'">';
                            $action .= method_field('delete');
                            $action .= csrf_field();
                            $action .=' <button type="submit" class="dropdown-item "   >';
                            $action .= '<i class="fa fa-trash"></i> '. __('delete');
                            $action .= '</button>';
                            $action .= '</form>';
                        endif;
                        if(hasPermission('business_branch_read')):
                            $action .= '<a href="'. route('business.branch.index',$business->id) .'" class="dropdown-item" ><i class="fa fa-eye"></i> '.__('branch') .'</a>';
                        endif;
                        $action .= '</div>';
                        $action .= '</div>'; 
                else:
                    return '<i class="fa fa-ellipsis"></i>';
                endif;
                return $action;
        })
        ->rawColumns(['business_name', 'logo',  'start_date', 'currency', 'sku_prefix', 'default_profit_percent', 'owner', 'status', 'action'])
        ->make(true);
        
    }
    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('business::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(CreateRequest $request)
    {
 
        if(env('DEMO')) {
            Toastr::error(__('store_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }
        if($this->businessRepo->CreateBusiness($request)){
            Toastr::success(__('business_added_successfully'),__('success'));
            return redirect()->route('business.index');
        }else{
            Toastr::error(__('error'),__('errors'));
            return redirect()->back();
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('business::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $business = $this->businessRepo->getFind($id);
        return view('business::edit',compact('business'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(StoreRequest $request)
    {
        if(env('DEMO')) {
            Toastr::error(__('update_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }
        if($this->businessRepo->update($request->id,$request)){
            Toastr::success(__('business_updated_successfully'),__('success'));
            return redirect()->route('business.index');
        }else{
            Toastr::error(__('error'),__('errors'));
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if(env('DEMO')) {
            Toastr::error(__('delete_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }
        if($this->businessRepo->delete($id)){
            Toastr::success(__('business_deleted_successfully'),__('success'));
            return redirect()->back();
        }else{
            Toastr::error(__('error'),__('errors'));
            return redirect()->back();
        }
    }


    public function statusUpdate($id)
    {
        if(env('DEMO')) {
            Toastr::error(__('update_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }
        if($this->businessRepo->statusUpdate($id)){
            Toastr::success(__('business_updated_successfully'),__('success'));
            return redirect()->back();
        }else{
            Toastr::error(__('error'),__('errors'));
            return redirect()->back();
        }
    }
}





