<?php
namespace App\Repositories\LoginActivity;

use App\Models\Backend\LoginActivity;
use App\Repositories\LoginActivity\LoginActivityInterface;
use Illuminate\Support\Facades\Auth;

class LoginActivityRepository implements LoginActivityInterface {

    public function getLatest(){
            return LoginActivity::latest()->get();
    }
    //add login activity history
    public function addLoginActivity($user_agent,$activity=''){
        try {
            $loginActivity            = new LoginActivity();
            $loginActivity->user_id   = Auth::user()->id;
            $loginActivity->activity  = $activity;
            $loginActivity->ip        = \Request::ip();
            $loginActivity->browser   = UserBrowser($user_agent);
            $loginActivity->os        = UserOS($user_agent);
            $loginActivity->device    = UserDevice($user_agent);
            $loginActivity->save();
            return true;
        } catch (\Throwable $th) {
           return false;
        }

    }
}
