<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Repositories\Settings\SettingsInterface;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan; 
class SettingsController extends Controller
{
    protected $repo;
    public function __construct(SettingsInterface $repo)
    {
        $this->repo  = $repo;
    }
    public function updateSettings(Request $request){
      
        if($this->repo->updateSettings($request)):
         
            Toastr::success(__('settings_updated'),__('success'));
            return redirect()->back();
        else:
            Toastr::error(__('error'),__('errors'));
            return redirect()->back()->withInput();
        endif;
    }


    public function paymentSettingsIndex(){
        return view('backend.settings.payment_settings.index');
    }
}
