<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Repositories\GeneralSettings\GeneralSettingsInterface;
use App\Repositories\Language\LanguageInterface;
use App\Repositories\Settings\SettingsInterface;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class GeneralSettingsController extends Controller
{


    protected $language;
    protected $settingsRepo;
    public  function __construct(LanguageInterface $language,SettingsInterface $settingsRepo)
    {

        $this->language     = $language;
        $this->settingsRepo = $settingsRepo;
       
    }
    public function index(){
        $languages          = $this->language->activelang();
        return view('backend.general_settings.index',compact('languages'));
    }

    //update general settings
    public function generalSettingsUpdate(Request $request){

        if($this->settingsRepo->updateSettings($request)):
            Toastr::success(__('general_settings_updated'),__('success'));
            return redirect()->route('settings.general.settings.index');
        else:
            Toastr::error(__('error'),__('errors'));
            return redirect()->back();
        endif;
    }
}
