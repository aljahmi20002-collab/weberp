<?php

namespace App\Http\Controllers\Auth;

use App\Enums\BanUser;
use App\Enums\Gender;
use App\Enums\Status;
use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Backend\Role;
use App\Models\Upload;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Repositories\LoginActivity\LoginActivityInterface;
use App\Repositories\Upload\UploadInterface;
use Brian2694\Toastr\Facades\Toastr;
use GuzzleHttp\Psr7\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Modules\Branch\Repositories\BranchInterface;
use Modules\Business\Repositories\BusinessInterface;
use Modules\Plan\Entities\Plan;
use Modules\Plan\Enums\IsDefault;
use Modules\Subscription\Repositories\SubscriptionInterface;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */



    use AuthenticatesUsers;

    protected $upload;
    protected $loginActivity,$businessRepo,$branchRepo,$subsRepo;
    public function __construct(
            UploadInterface $upload,
            LoginActivityInterface $loginActivity,
            BusinessInterface $businessRepo,
            BranchInterface   $branchRepo,
            SubscriptionInterface $subsRepo
        )
    {
        $this->middleware('guest')->except('logout');
        $this->upload         = $upload;
        $this->loginActivity  = $loginActivity;
        $this->businessRepo   = $businessRepo;
        $this->branchRepo     = $branchRepo;
        $this->subsRepo       = $subsRepo;

    }

    public function demoLogin(HttpRequest $request){
        $user     = User::where('email',$request->email)->first();
        Auth::login($user);
        //add user login activity
        if(Auth::check()):
            $this->loginActivity->addloginActivity(\Request::header('user_agent'),'user_logged_in');
        endif;
        //end user login  activity
        Toastr::success('Logged successfully.','Success');
        return $this->sendLoginResponse($request);
    }


    public function login(LoginRequest $request){

        //remember
        if($request->remember != null)
        {
            Cookie::queue('useremail',   $request->email,1440);
            Cookie::queue('userpassword',$request->password,1440);
        }
        else
        {
            Cookie::queue(Cookie::forget('useremail'));
            Cookie::queue(Cookie::forget('userpassword'));
        }
        //end remember
 
        $user    =  User::where('email',$request->email)->first();

        if($user && $user->email_verified != 1){
            return redirect()->back()->withErrors([
                'email' => __('Account is not verified.')
            ])->withInput();
        }

        if($user && $user->status == Status::INACTIVE){
            return redirect()->back()->withErrors([
                'email' => __('account_inactive')
            ])->withInput();
        }

        if($user && $user->is_ban == BanUser::BAN){
            return redirect()->back()->withErrors([
                'email' => __('auth.banned_account')
            ])->withInput();
        }

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }
        $remember    = $request->remember ? true :false;
        if ($this->attemptLogin($request,$remember)) {
            if ($request->hasSession()) {
                $request->session()->put('auth.password_confirmed_at', time());
            }

            //add user login activity
            if(Auth::check()):
                $this->loginActivity->addloginActivity(\Request::header('user_agent'),'user_logged_in');
            endif;
            //end user login  activity

            Toastr::success('Logged successfully.','Success');
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        if($user && !Hash::check($request->email,$user->password)){
            return redirect()->back()->withErrors([
                'password'      => trans('auth.password'),
            ])->withInput();
        }

        return redirect()->back()->withErrors([
            'email'         => trans('auth.failed'),
        ])->withInput();

    }


    //social authentication

    //google login
    public function socialRedirect($social){

        if($social == 'google'){
            if(settings('google_status') != Status::ACTIVE):
                Toastr::error('Google login is not enabled.',__('errors'));
                return redirect()->back();
            endif;
            \config([
                'services.google.client_id'     => settings('google_client_id'),
                'services.google.client_secret' => settings('google_client_secret'),
                'services.google.redirect'      =>  url('google/login')
            ]);
            return Socialite::driver('google')->redirect();
        }elseif($social == 'facebook'){

            if(settings('facebook_status') != Status::ACTIVE):
                Toastr::error('Facebook login is not enabled.',__('errors'));
                return redirect()->back();
            endif;
            \config([
                'services.facebook.client_id'     => settings('facebook_client_id'),
                'services.facebook.client_secret' => settings('facebook_client_secret'),
                'services.facebook.redirect'      => url('facebook/login')
            ]);
            return Socialite::driver('facebook')->redirect();

        }
        return redirect()->back();
    }

    public function authGoogleLogin(HttpRequest $request){
        try {
            \config([
                'services.google.client_id'     => settings('google_client_id'),
                'services.google.client_secret' => settings('google_client_secret'),
                'services.google.redirect'      =>  url('google/login')
            ]);
            $user      = Socialite::driver('google')->user();
            $existUser = User::where('google_id',$user->id)->first();
            if($existUser){
                Auth::login($existUser);

                //add user login activity
                if(Auth::check()):
                    $this->loginActivity->addloginActivity(\Request::header('user_agent'),'user_logged_in');
                endif;
                //end user login  activity

                return redirect('/');
            }else{
                $role = Role::find(2);
                $plan = Plan::where(['status'=>Status::ACTIVE,'is_default'=>IsDefault::YES])->first();
                $new_user = User::create([
                    'name'           => $user->name,
                    'email'          => $user->email,
                    'google_id'      => $user->id,
                    'user_type'      => UserType::ADMIN,
                    'avatar'         => $this->upload->linktoAvatarUpload($user,$user->avatar_original), 
                    'role_id'        => $role ? $role->id : null,
                    'permissions'    => businessPlanPermission($plan->id),
                    'email_verified_at' => Date('Y-m-d H:i:s'),
                    'email_verified'    => 1,
                    'password'       => Str::random(32),
                ]);


                if($user):

                    $request['business_name'] = $user->name;
                    $request['start_date']    = Date('Y-m-d H:i:s'); 
                    $request['owner_id']      = $new_user->id;
                    $request['currency']      = 124;
                    $request['signup']        = true;

                    $business_id         = $this->businessRepo->store($request);//create business account 
                    if($business_id):
                        $request['business_id'] = $business_id;
                        $request['branch_name'] = $request->business_name;
                        $request['status']      = 'on';
                        $branch                 = $this->branchRepo->store($request);

                        //subscription
                        $plan                 = Plan::where(['status'=>Status::ACTIVE,'is_default'=>IsDefault::YES])->first();
                        $request['plan_id']   = $plan->id;
                        $request['paid_via']  = $plan->name;
                        $this->subsRepo->subscription($request);
                    endif;
                endif;
 
                Auth::login($new_user);

                //add user login activity
                if(Auth::check()):
                    $this->loginActivity->addloginActivity(\Request::header('user_agent'),'user_logged_in');
                endif;
                //end user login  activity 
                return redirect('/');
            }

        } catch (\Throwable $th) {
            return redirect()->back();
        }
    }
    //end google login

 
    //facebook login
    public function authFacebookLogin(){

        try {

            \config([
                'services.facebook.client_id'     => settings('facebook_client_id'),
                'services.facebook.client_secret' => settings('facebook_client_secret'),
                'services.facebook.redirect'      => url('facebook/login')
            ]);

            $user      = Socialite::driver('facebook')->user();

            $existUser = User::where('facebook_id',$user->id)->first();
            if($existUser){
                Auth::login($existUser);

                //add user login activity
                if(Auth::check()):
                    $this->loginActivity->addloginActivity(\Request::header('user_agent'),'user_logged_in');
                endif;
                //end user login  activity
                return redirect('/');
            }else{
                $role = Role::find(2);
                $new_user = User::create([
                    'name'           => $user->name,
                    'email'          => $user->email,
                    'facebook_id'    => $user->id,
                    'avatar'         => $this->upload->linktoAvatarUpload($user,$user->avatar_original),
                    'gender'         => Gender::OTHER,
                    'role_id'        => $role ? $role->id : null,
                    'permissions'    => $role ? $role->permissions : [],
                    'password'       => Str::random(32),
                ]);
                Auth::login($new_user);

                //add user login activity
                if(Auth::check()):
                    $this->loginActivity->addloginActivity(\Request::header('user_agent'),'user_logged_in');
                endif;
                //end user login  activity

                return redirect('/');
            }

        } catch (\Throwable $th) {
            Toastr::error(__('error'),__('errors'));
            return redirect()->back();
        }
    }

    //end facebook login


    //end social authentication


    //logout
    public function logout(HttpRequest $request){
        //add user  logout activity
        $this->loginActivity->addLoginActivity(\Request::header('user_agent'),'user_logged_out');
        //end user  logout activity

        $this->guard()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        if ($response = $this->loggedOut($request)) {
            return $response;
        }
        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect('/');
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
}
