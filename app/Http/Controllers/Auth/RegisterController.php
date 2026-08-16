<?php

namespace App\Http\Controllers\Auth;

use App\Enums\Status;
use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Models\Backend\Role;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Repositories\Auth\AuthInterface;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Modules\Branch\Repositories\BranchInterface;
use Modules\Business\Repositories\BusinessInterface;
use Modules\Plan\Entities\Plan;
use Modules\Plan\Enums\IsDefault;
use Modules\Subscription\Repositories\SubscriptionInterface;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $repo,$businessRepo,$branchRepo,$subsRepo;
    public function __construct(AuthInterface $repo,BusinessInterface $businessRepo,BranchInterface $branchRepo,SubscriptionInterface $subsRepo)
    {
        $this->middleware('guest');
        $this->repo   = $repo;
        $this->businessRepo = $businessRepo;
        $this->branchRepo   = $branchRepo;
        $this->subsRepo     = $subsRepo;
    }


    public function register(Request $request)
    {
        $this->validator($request->all())->validate();
        event(new Registered($user = $this->create($request->all())));
        if($user):
            $request['owner_id'] = $user->id;
            $request['signup']   = true;
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
        if($user && !$this->repo->registermailSend($user)):
            Toastr::error('Mail server is not working. Please try again',__('error'));
            return redirect()->back();
        endif;
        if ($response = $this->registered($request, $user)) {
            return $response;
        }
        $resend =  "if you don't received the verification mail, please click the resend. "."<a  href='".route('register.resend.verify.mail',['email'=>$user->email])."'>Resend</a>";
        return redirect()->back()->with('status','We have sended email verification link.Please check your inbox.'.$resend);

    }

    public function resendRegisterMail(Request $request){
        $user = User::where(['email'=>$request->email])->first();
        if($user && !$this->repo->registermailSend($user)):
            Toastr::error('Mail server is not working. Please try again',__('errors'));
            return redirect()->back();
        endif;
        $resend =  "if you don't received the verification mail, please click the resend. "."<a   href='".route('register.resend.verify.mail',['email'=>$user->email])."'>Resend</a>";
        return redirect()->back()->with('status','We have sended email verification link.Please check your inbox.'.$resend);

    }


    public function VerifyNow(Request $request){
        $user = User::where(['email'=>$request->email,'verify_token'=>$request->verify_token])->first();
        if(!$user):
            Toastr::error('token has expired.',__('errors'));
            return redirect()->route('login');
        endif;
        if($this->repo->verifyNow($request)):
            return redirect('/login')->with('status','Account has been activated.');
        else:
            Toastr::error(__('error'),__('errors'));
            return redirect()->route('login');
        endif;
    }


    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {

        if(settings('recaptcha_status') == 1):
            $recaptcha  = 'required|string';
        else:
            $recaptcha  = '';
        endif;

        return  Validator::make($data, [
            //business details
            'business_name'     =>  ['required'],
            'start_date'        =>  ['required'],
            'logo'              =>  ['mimes:png,jpg'],
            'currency'          =>  ['required'],
            'country'           =>  ['required'],
            'website'           =>  ['required'],
            'business_phone'    =>  ['required','numeric'],
            'state'             =>  ['required'],
            'city'              =>  ['required'],
            'zip_code'          =>  ['required'],
            //owner information
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'      => ['required', 'string', 'min:8', 'confirmed'],
            'g-recaptcha-response' => $recaptcha
        ],[
            'gender.required'              => __('gender_required'),
            'g-recaptcha-response.required'=>'Please verify that you are not a robot.'

        ]);


    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {


        $role = Role::find(2);
        $plan = Plan::where(['status'=>Status::ACTIVE,'is_default'=>IsDefault::YES])->first();
        $user = User::create([
            'name'           => $data['name'],
            'email'          => $data['email'],
            'phone'          => $data['business_phone'],
            'user_type'      => UserType::ADMIN,
            'role_id'        => $role ? $role->id : null,
            'permissions'    => businessPlanPermission($plan->id),
            'password'       => Hash::make($data['password']),
            'business_owner' => 1,
            'verify_token'   => \Str::random(16),
        ]);
        return $user; 
    }
}
