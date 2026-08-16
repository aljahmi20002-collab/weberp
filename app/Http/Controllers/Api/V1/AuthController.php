<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\BanUser;
use App\Enums\Status;
use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotRequest;
use App\Http\Resources\v1\UserResource;
use App\Models\Backend\Role;
use App\Models\User;
use App\Repositories\Auth\AuthInterface;
use Illuminate\Http\Request;
use App\Traits\ApiReturnFormatTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
 
use Illuminate\Support\Facades\Hash;
use Modules\Branch\Repositories\BranchInterface;
use Modules\Business\Repositories\BusinessInterface;
use Modules\Currency\Entities\Currency;
use Modules\Plan\Entities\Plan;
use Modules\Plan\Enums\IsDefault;
use Modules\Subscription\Repositories\SubscriptionInterface;
use Illuminate\Support\Str;
class AuthController extends Controller
{
    use ApiReturnFormatTrait;

    protected $businessRepo, $branchRepo, $subsRepo, $repo;
    public function __construct(
        BusinessInterface      $busnessRepo,
        BranchInterface        $branchRepo,
        SubscriptionInterface  $subsRepo,
        AuthInterface          $repo
    ) {
        $this->businessRepo    = $busnessRepo;
        $this->branchRepo      = $branchRepo;
        $this->subsRepo        = $subsRepo;
        $this->repo            = $repo;
    }

    public function login(Request $request)
    {

        $attr = Validator::make($request->all(), [
            'email'     => 'required|string',
            'password'  => 'required|string|min:6'
        ]);

        if ($attr->fails()) :
            return $this->responseWithError(__('error'), ['message'=>$attr->errors()], 422);
        endif;
 
        if (is_numeric($request->get('email'))) {
            $attr = ['mobile' => $request->get('email'), 'password' => $request->get('password')];
        } elseif (filter_var($request->get('email'), FILTER_VALIDATE_EMAIL)) {
            $attr =  ['email' => $request->get('email'), 'password' => $request->get('password')];
        }
        if (!Auth::attempt($attr)) {
            return $this->responseWithError(__('auth.credentials_msg'), [], 401);
        }

        if (Auth::user() && Auth::user()->email_verified != 1) {
            Auth::guard()->logout();
            return $this->responseWithError(__('Account is not verified.'), [], 400);
        }
        if (Auth::user() && Auth::user()->status == Status::INACTIVE) {
            Auth::guard()->logout();
            return $this->responseWithError(__('account_inactive'), [], 400);
        }
        if (Auth::user() && Auth::user()->is_ban == BanUser::BAN) {
            Auth::guard()->logout();
            return $this->responseWithError(__('auth.banned_account'), [], 400);
        }
        return $this->responseWithSuccess(__('auth.signin_msg'), ['token' => Auth::user()->createToken($request->email)->plainTextToken, 'user' => new UserResource(Auth::user())], 200);
    }

    public function profile()
    {
        return $this->responseWithSuccess(__('auth.profile_msg'), ['user' => new UserResource(Auth::user())], 200);
    }

    public function refresh(Request $request)
    {
        Auth::user()->tokens()->delete();
        return $this->responseWithSuccess(__('auth.token_refresh'), ['token' =>  Auth::user()->createToken(Auth::user()->email)->plainTextToken], 200);
    }

    public function logout(Request $request)
    {
        Auth::user()->tokens()->delete();
        return $this->responseWithSuccess(__('auth.token_delete'), [], 200);
    }


    public function countries(){
        return $this->responseWithSuccess('Country list',['countries'=>Currency::orderByDesc('position')->get()],200);
    }
    public function register(Request $request)
    {
         
        $validator = Validator::make($request->all(), [
            //business details
            'business_name'     =>  ['required'],
            'start_date'        =>  ['required'],
            'logo'              =>  ['mimes:png,jpg'],
            'currency'          =>  ['required', 'numeric'],
            'country'           =>  ['required', 'numeric'],
            'website'           =>  ['required'],
            'business_phone'    =>  ['required', 'numeric'],
            'state'             =>  ['required'],
            'city'              =>  ['required'],
            'zip_code'          =>  ['required'],
            //owner information
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'      => ['required', 'string', 'min:8']
        ]);

        if ($validator->fails()) :
            return $this->responseWithError(__('error'),[ 'message'=>$validator->errors()], 422);
        endif;

        $role = Role::find(2);
        $plan = Plan::where(['status' => Status::ACTIVE, 'is_default' => IsDefault::YES])->first();
        $user = User::create([
            'name'           => $request->name,
            'email'          => $request->email,
            'phone'          => $request->business_phone,
            'user_type'      => UserType::ADMIN,
            'role_id'        => $role ? $role->id : null,
            'permissions'    => businessPlanPermission($plan->id),
            'password'       => Hash::make($request->password),
            'business_owner' => 1,
            'verify_token'   => random_int(10000, 99999)
        ]);

        if ($user) :
            $request['owner_id'] = $user->id;
            $request['signup']   = true;
            $business_id         = $this->businessRepo->store($request); //create business account 

            if ($business_id) :
                $request['business_id'] = $business_id;
                $request['branch_name'] = $request->business_name;
                $request['status']      = 'on';
                $branch                 = $this->branchRepo->store($request);

                //subscription
                $plan                 = Plan::where(['status' => Status::ACTIVE, 'is_default' => IsDefault::YES])->first();
                $request['plan_id']   = $plan->id;
                $request['paid_via']  = $plan->name;
                $this->subsRepo->subscription($request);
            endif;
        endif;
        session(['otp' => $user->verify_token]);
        if ($user && !$this->repo->registermailSend($user)) :
            return $this->responseWithError('Mail server is not working. Please try again', [], 400);
        endif;
        return $this->responseWithSuccess('We have sended email verification one time password (OTP). Please check your inbox.', [], 200);
    }
 
    public function resendOTP(Request $request)
    {
        $validator   = Validator::make($request->all(), [
            'email'  => ['required', 'string', 'email']
        ]);
        if ($validator->fails()) :
            return $this->responseWithError(__('error'), ['message'=>$validator->errors()], 422);
        endif;
        $user = User::where(['email' => $request->email])->first();
        if ($user) :
            if (!$user->verify_token) :
                return $this->responseWithError('This account already verifyed', [], 400);
            endif;
            session(['otp' => $user->verify_token]);
            if ($user && !$this->repo->registermailSend($user)) :
                return $this->responseWithError('Mail server is not working. Please try again', [], 400);
            endif;
            return $this->responseWithSuccess('We have sended email verification one time password (OTP). Please check your inbox.', [], 200);
        else :
            return $this->responseWithError(__('auth.credentials_msg'), [], 401);
        endif;
    }


    public function VerifyNow(Request $request)
    {

        $validator   = Validator::make($request->all(), [
            'email'        => ['required', 'email'],
            'verify_token' => ['required']
        ]);
        if ($validator->fails()) :
            return $this->responseWithError(__('error'), ['message'=>$validator->errors()], 400);
        endif;

        $user = User::where(['email' => $request->email, 'verify_token' => $request->verify_token])->first();
        if (!$user) :
            return $this->responseWithError('Invalid your OTP.', [], 400);
        endif;
        if ($this->repo->verifyNow($request)) :
            return $this->responseWithSuccess('Account has been activated.', [], 200);
        else:
            return $this->responseWithError(__('error'), [], 400);
        endif;
    }
     
    public function resetPasswordOtpSend(Request $request){
        $validator  = Validator::make($request->all(),[
            'email' =>  ['required','email']
        ]);
        if($validator->fails()):
            return $this->responseWithError(__('error'), $validator->errors(),400);
        endif;
        $user = User::where('email',$request->email)->first();
        if(!$user):
            return $this->responseWithError(__('error'),$validator->errors()->add('email',"We can't find a user with that email address."),400);
        endif;
        $request['otp']  = random_int(10000, 99999); 
        if($this->repo->passwordResetlink($request)):
            return $this->responseWithSuccess(__('success'),[
                'message'=>'We have sended password reset one time password (OTP). Please check your email.',
                'otp'=>$request->otp],200);
        else:
           return $this->responseWithError(__('error'),['message'=>'Mail server is not working. Please try again'],400);
            return redirect()->back();
        endif;
    }
  
    public function passwordUpdate(Request $request){
 
        $validator = Validator::make($request->all(),[
            'password' => 'required|min:8',
            'password_confirmation' => 'required|same:password',
            'otp'      => ['required']
        ]);
        $request['token']  = $request->otp;
        if($validator->fails()):
            return $this->responseWithError(__('error'),['message'=>$validator->errors()],400);
        endif; 
        $user = User::where('email',$request->email)->first();
        if(!$user):
            return $this->responseWithError(__('error'),['message' =>"We can't find a user with that email address." ],400);
        endif;
        
     
        if($user->forgot_token !== $request->token): 
             return $this->responseWithError(__('error'),['message'=>'Token has expired.'],400);
        endif;
        if($this->repo->passwordUpdate($request)): 
            return $this->responseWithSuccess(__('success'),['message'=>'Password successfully changes.'],200);
        else: 
            return $this->responseWithError(__('error'),[],400);
        endif; 
    }


    public function permissions(){
    
        return $this->responseWithSuccess(__('parmission_list'),['permission_list'=>Auth::user()->permissions],200);
    }

}
