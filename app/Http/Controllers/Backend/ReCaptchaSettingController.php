<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Repositories\Settings\SettingsInterface;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
class ReCaptchaSettingController extends Controller
{
    protected $settingsRepo;
    public function __construct(SettingsInterface $settingsRepo)
    {
        $this->settingsRepo = $settingsRepo;
    }
    public function index(){

        return view('backend.recaptcha_settings.index');
    }

    public function update(Request $request){
        if($this->settingsRepo->updateSettings($request)):
            Toastr::success(__('recaptcha_updated'),__('success'));
            return redirect()->route('settings.recaptcha.index');
        else:
            Toastr::error(__('error'),__('errors'));
            return redirect()->back()->withInput();
        endif;
    }


}
