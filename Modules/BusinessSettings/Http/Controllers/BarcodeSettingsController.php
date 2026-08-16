<?php

namespace Modules\BusinessSettings\Http\Controllers;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\BusinessSettings\Http\Requests\BarcodeSettingStoreRequest;
use Modules\BusinessSettings\Repositories\BarcodeSettings\BarcodeSettingsInterface;

class BarcodeSettingsController extends Controller
{
    protected $repo;
    public function __construct(BarcodeSettingsInterface $repo)
    {
        $this->repo = $repo;
    }
   
 
    public function create()
    {
        return view('businesssettings::barcode_settings.create');
    }
 
    public function store(BarcodeSettingStoreRequest $request)
    {
        if(env('DEMO')) {
            Toastr::error(__('store_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }

        if($this->repo->store($request)): 
            Toastr::success(__('barcode_setting_added_successfully'), __('success'));
            return redirect()->back();
        else:
            Toastr::error(__('error'), __('errors'));
           return redirect()->back();
        endif;
    }
 
    public function edit($id)
    {
        $barcodeSetting   = $this->repo->getFind($id);
        return view('businesssettings::barcode_settings.edit',compact('barcodeSetting'));
    }
    
    public function update(BarcodeSettingStoreRequest $request)
    {
        if(env('DEMO')) {
            Toastr::error(__('update_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }

        if($this->repo->update($request->id,$request)): 
            Toastr::success(__('barcode_setting_updated_successfully'), __('success'));
            return redirect()->back();
        else:
            Toastr::error(__('error'), __('errors'));
           return redirect()->back();
        endif;
    }

    
    public function delete($id)
    {
        if(env('DEMO')) {
            Toastr::error(__('delete_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }

        if($this->repo->delete($id)): 
            Toastr::success(__('barcode_setting_deleted_successfully'), __('success'));
            return redirect()->back();
        else:
            Toastr::error(__('error'), __('errors'));
           return redirect()->back();
        endif;
    }
}
