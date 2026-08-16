<?php

namespace App\Http\Controllers\Backend\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\ChangePassword;
use App\Http\Requests\Profile\ProfileRequest;
use App\Models\User;
use App\Repositories\Profile\ProfileInterface;
use App\Repositories\Upload\UploadInterface;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class ProfileController extends Controller
{
    protected $repo;
    protected $upload;
    public function __construct(ProfileInterface $repo,UploadInterface $upload)
    {
        $this->repo   = $repo;
        $this->upload = $upload;
    }

    //profile update
    public function profile(Request $request){
        return view('backend.profile.profile',compact('request'));
    }

    public function ProfileUpdate(ProfileRequest $request){

        if(env('DEMO')) {
            Toastr::error(__('Update system is disable for the demo mode.'),__('errors'));
            return redirect()->back()->withInput();
        }

        if($this->repo->profileUpdate($request)):
            Toastr::success(__('profile_update'),__('success'));
            return redirect()->route('profile.index')->withInput($request->only('profile_name'));
        else:
            Toastr::error(__('error'),__('errors'));
            return redirect()->back()->withInput();
        endif;
    }
    //end profile update

    public function ProfileUpdateAccount(ProfileRequest $request){
        if(env('DEMO')) {
            Toastr::error(__('Update system is disable for the demo mode.'),__('errors'));
            return redirect()->back()->withInput();
        }
        if($this->repo->updateAccount($request)):
            Toastr::success(__('account_update'),__('success'));
            return redirect()->route('profile.index')->withInput($request->only('account_change'));
        else:
            Toastr::error(__('error'),__('errors'));
            return redirect()->back()->withInput();
        endif;
    }
    // end profile account


    public function profileUpdateAvatar(Request $request){
        if($this->repo->updateAvatar($request)):
            Toastr::success(__('avatar_update'),__('success'));
            return redirect()->route('profile.index')->withInput($request->only('avatar_change'));
        else:
            Toastr::error(__('error'),__('errors'));
            return false;
        endif;

    }
    //end avatar change



    public function UpdatePassword(ChangePassword $request){
        if(env('DEMO')) {
            Toastr::error(__('Update system is disable for the demo mode.'),__('errors'));
            return redirect()->back()->withInput();
        }
        if($this->repo->UpdatePassword($request) === 'invalid_password'):
            return redirect()->back()->withErrors([
                'current_password' => __('validation.invalid',['attribute'=>__('levels.current_password')])
            ])->withInput();
        elseif($this->repo->UpdatePassword($request)):
            Toastr::success(__('password_updated'),__('success'));
            return redirect()->back()->withInput($request->only('change_password'));
        else:
            Toastr::error(__('error'),__('errors'));
            return redirect()->back()->withInput();
        endif;
    }
    //end password change

}
