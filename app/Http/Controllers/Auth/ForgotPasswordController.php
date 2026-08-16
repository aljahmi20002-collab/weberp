<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotRequest;
use App\Models\User;
use App\Repositories\Auth\AuthInterface;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;
    protected $repo;
    public function __construct(AuthInterface $repo)
    {
        $this->repo = $repo;
    }
    public function passwordResetLink(Request $request){

        $user = User::where('email',$request->email)->first();
        if(!$user):
            return redirect()->back()->withErrors([
                'email' =>"We can't find a user with that email address."
            ]);
        endif;
        if($this->repo->passwordResetlink($request)):
            return redirect()->back()->with('status','We have sended password reset link.');
        else:
            Toastr::error('Mail server is not working. Please try again',__('errors'));
            return redirect()->back();
        endif;
    }

    public function passwordReset($token){
        return view('auth.passwords.reset',compact('token'));
    }

    public function passwordUpdate(ForgotRequest $request){
        $user = User::where('email',$request->email)->first();
        if(!$user):
            return redirect()->back()->withErrors([
                'email' =>"We can't find a user with that email address."
            ]);
        endif;

        if($user->forgot_token !== $request->token):
            Toastr::error('token has expired.',__('errors'));
            return redirect('password/reset/'.$request->token.'?email='.$request->email);
        endif;

        if($this->repo->passwordUpdate($request)):
            Toastr::success('Password successfully changes',__('success'));
            return redirect()->route('login');
        else:
            Toastr::error(__('error'),__('errors'));
            return redirect('password/reset/'.$request->token.'?email='.$request->email);
        endif;
    }
}
