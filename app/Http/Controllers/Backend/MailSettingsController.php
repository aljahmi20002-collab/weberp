<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\MailSendTestRequest;
use App\Mail\Auth\RegisterMail;
use App\Repositories\MailSettings\MailSettingsInterface;
use App\Repositories\Settings\SettingsInterface;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailSettingsController extends Controller
{
    protected $repo;
    protected $settingsRepo;

    public function __construct(MailSettingsInterface $repo,SettingsInterface $settingsRepo)
    {
        $this->repo    = $repo;
        $this->settingsRepo = $settingsRepo;
    }
    public function index(){

        return view('backend.mail_settings.index');
    }

    public function update(Request $request){

        if($this->settingsRepo->updateSettings($request)):
            Toastr::success(__('mail_settings_updated'),__('success'));
            return redirect()->route('settings.mail.settings.index');
        else:
            Toastr::error(__('error'),__('errors'));
            return redirect()->back();
        endif;
    }

    public function testSendMail(MailSendTestRequest $request){
        if($this->repo->mailSendTest($request)):
            Toastr::success(__('mail_send_test'),__('success'));
            return redirect()->route('settings.mail.settings.index');
        else:
            Toastr::error(__('mail_error'),__('errors'));
            return redirect()->back();
        endif;
    }
}
