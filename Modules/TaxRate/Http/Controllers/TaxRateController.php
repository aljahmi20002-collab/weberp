<?php

namespace Modules\TaxRate\Http\Controllers;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Business\Repositories\BusinessInterface;
use Modules\TaxRate\Http\Requests\StoreRequest;
use Modules\TaxRate\Repositories\TaxRateInterface;

class TaxRateController extends Controller
{
     protected $repo,$businessRepo;
     public function __construct(TaxRateInterface $repo, BusinessInterface $businessRepo)
     {
        $this->repo           = $repo;
        $this->businessRepo   = $businessRepo;
     }
    public function index()
    {
        $taxRates = $this->repo->get();
        return view('taxrate::index',compact('taxRates'));
    }
 
    public function create()
    {
        $businesses     = $this->businessRepo->getAll();
        return view('taxrate::create',compact('businesses'));
    }
   
    public function store(StoreRequest $request)
    {
        if(env('DEMO')) {
            Toastr::error(__('store_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }
        if($this->repo->store($request)){
            Toastr::success(__('tax_rate_added_successfully'),__('errors'));
            return redirect()->back();
        }else{
            Toastr::error(__('error'),__('errors'));
            return redirect()->back();
        }
    }
  
    public function edit($id)
    {
        $businesses   = $this->businessRepo->getAll();
        $taxRate      = $this->repo->getFind($id);
        return view('taxrate::edit',compact('businesses', 'taxRate'));
    }
 
    public function update(StoreRequest $request)
    {
        if(env('DEMO')) {
            Toastr::error(__('update_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }
        if($this->repo->update($request->id,$request)){
            Toastr::success(__('tax_rate_updated_successfully'),__('success'));
            return redirect()->back();
        }else{
            Toastr::error(__('error'),__('errors'));
            return redirect()->back();
        }
    }
 
    public function destroy($id)
    {
        if(env('DEMO')) {
            Toastr::error(__('delete_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }
        if($this->repo->delete($id)){
            Toastr::success(__('tax_rate_deleted_successfully'),__('success'));
            return redirect()->back();
        }else{
            Toastr::error(__('error'),__('errors'));
            return redirect()->back();
        }
    }

    public function statusUpdate($id){
        if(env('DEMO')) {
            Toastr::error(__('update_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }
        if($this->repo->statusUpdate($id)){
            Toastr::success(__('tax_rate_update_successfully'),__('success'));
            return redirect()->route('settings.tax.rate.index');
        }else{
            Toastr::error(__('error'),__('errors'));
            return redirect()->back();
        }
    }
}
