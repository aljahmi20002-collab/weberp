<?php
namespace App\Repositories\User;

use App\Enums\BanUser;
use App\Enums\Status;
use App\Enums\UserType;
use App\Models\Backend\Role;
use App\Models\Upload;
use App\Repositories\User\UserInterface;
use App\Models\User;
use App\Repositories\Upload\UploadInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Modules\Business\Entities\Business;
use Modules\Plan\Entities\Plan;
use Modules\Plan\Enums\IsDefault;
use Modules\Subscription\Entities\Subscription;
use Symfony\Component\CssSelector\Node\FunctionNode;

class UserRepository implements UserInterface
{

    protected $upload;
    public function __construct(UploadInterface $upload)
    {
        $this->upload = $upload;
    }
    public function get(){
        if(Auth::user()->user_type == UserType::ADMIN):
            if(Auth::user()->business):
                $business_id  = Auth::user()->business->id;
            else:
                $business_id  = Auth::user()->userBusiness->id;
            endif;
            $user = User::where(['business_id'=>$business_id])->orderByDesc('id')->paginate(10);
        elseif(Auth::user()->user_type == UserType::USER):
            $user = User::where(['user_type'=>UserType::USER,'business_id'=>Auth::user()->business_id,'branch_id'=>Auth::user()->branch_id])->orderByDesc('id')->paginate(10);
        elseif(Auth::user()->user_type == UserType::SUPERADMIN):
            $user = User::where(['user_type'=>UserType::ADMIN])->orWhere('user_type',UserType::SUPERADMIN)->orderByDesc('id')->paginate(10);
        else:
            $user = User::orderByDesc('id')->paginate(10);
        endif;
        return $user;
    }
    public function getAll(){
        if(Auth::user()->user_type == UserType::ADMIN):
            if(Auth::user()->business):
                $business_id  = Auth::user()->business->id;
            else:
                $business_id  = Auth::user()->userBusiness->id;
            endif;
            $user = User::where(['business_id'=>$business_id])->orderByDesc('id')->get();
        elseif(Auth::user()->user_type == UserType::USER):
            $user = User::where(['user_type'=>UserType::USER,'business_id'=>Auth::user()->business_id,'branch_id'=>Auth::user()->branch_id])->orderByDesc('id')->get();
        elseif(Auth::user()->user_type == UserType::SUPERADMIN):
            $user = User::where(['user_type'=>UserType::ADMIN])->orWhere('user_type',UserType::SUPERADMIN)->orderByDesc('id')->get();
        else:
            $user = User::orderByDesc('id')->get();
        endif;
        return $user;
    }
 

    public function getAllBusinessUsers(){
        return User::where(['status'=>Status::ACTIVE,'is_ban'=>BanUser::UNBAN,'user_type'=>UserType::ADMIN])->orderByDesc('id')->get();
    }

    public function getAttendanceUsers()
    {
        return User::where(function($query){
            if(business()): 
                $query->where(['business_id'=>business_id(),'status'=>Status::ACTIVE,'is_ban'=>BanUser::UNBAN])->orWhere(['id'=>Auth::user()->id]);
            elseif(isUser()):
                $query->where(['id'=>Auth::user()->id]);
            else:
                $query->where(['status'=>Status::ACTIVE,'user_type'=>UserType::ADMIN,'is_ban'=>BanUser::UNBAN]);
            endif;
        })->orderByDesc('id')->paginate(10,'*','summery');
    }
    public function getFilterAttendanceUsers($request)
    {
        return User::where(function($query)use($request){
                if(!empty($request->search)):
                    $query->where('name','like','%' . $request->search.'%');
                    $query->orWhere('email','like','%' . $request->search.'%');
                endif;
            })->where(function($query){
               
            if(business()): 
                $query->where(['business_id'=>business_id(),'status'=>Status::ACTIVE,'is_ban'=>BanUser::UNBAN])->orWhere(['id'=>Auth::user()->id]);
            elseif(isUser()):
                $query->where(['id'=>Auth::user()->id]);
            else:
                $query->where(['status'=>Status::ACTIVE,'user_type'=>UserType::ADMIN,'is_ban'=>BanUser::UNBAN]);
            endif;
        })->orderByDesc('id')->paginate(10,'*','summery');
    }
 
    public function getReportsUsers()
    {
        return User::where(function($query){
            if(business()): 
                $query->where(['business_id'=>business_id(),'status'=>Status::ACTIVE,'is_ban'=>BanUser::UNBAN])->orWhere(['id'=>Auth::user()->id]);
            elseif(isUser()):
                $query->where(['id'=>Auth::user()->id]);
            else:
                $query->where(['status'=>Status::ACTIVE,'user_type'=>UserType::ADMIN,'is_ban'=>BanUser::UNBAN]);
            endif;
        })->orderByDesc('id')->get();
    }
    public function getUsers()
    {
        return User::where(function($query){
            if(business()): 
                $query->where(['business_id'=>business_id(),'status'=>Status::ACTIVE,'is_ban'=>BanUser::UNBAN])->orWhere(['id'=>Auth::user()->id]);
            elseif(isUser()): 
                $query->where(['business_id'=>business_id(),'branch_id'=>Auth::user()->branch_id]);
            else:
                $query->where(['status'=>Status::ACTIVE,'user_type'=>UserType::ADMIN,'is_ban'=>BanUser::UNBAN]);
            endif;
        })->orderByDesc('id')->get();
    }

    public function LeaveApplyUser(){
        if(isSuperadmin()):
            $user = User::where(['business_owner'=>1,'user_type'=>UserType::ADMIN])->orWhere('user_type',UserType::ADMIN)->orderByDesc('id')->get();
        else:
            $user = User::where(['business_id'=>business_id()])->orderByDesc('id')->get();
        endif;
        return $user;
    }

    public function attendanceUser(){
        if(isSuperadmin()):
            $user = User::where(['user_type'=>UserType::ADMIN,'status'=>Status::ACTIVE,'is_ban'=>BanUser::UNBAN])->orderByDesc('id')->get();
        else:
            $user = User::where(['status'=>Status::ACTIVE,'is_ban'=>BanUser::UNBAN])
                          ->where(function($query){
                            $query->where(['business_id'=>business_id()]);
                            $query->orWhere(['id'=>Auth::user()->id]);
                          })->orderByDesc('id')->get();
        endif;
        return $user;
    }

    //add new user
    public function store($request){
        try {

            $role                 =  Role::find($request->role);
            $user                 =  new User();
            $user->name           =  $request->name;
            $user->email          =  $request->email;
            $user->user_type      =  $request->user_type;
            if($request->user_type == UserType::SUPERADMIN):
                $user->role_id        =  Role::find(1)->id;
            elseif($request->user_type == UserType::ADMIN):
                $user->role_id        = Role::find(2)->id;
            else:
                $user->role_id        = Role::find(3)->id;
            endif;
            $user->phone          =  $request->phone;
            $user->address        =  $request->address;
            $user->about          =  $request->about;
            $user->password       =  Hash::make($request->password);
            if($request->designation_id):
                $user->designation_id = $request->designation_id;
            endif;
            if($request->department_id):
                $user->department_id = $request->department_id;
            endif;

            if(Auth::user()->user_type == UserType::ADMIN || Auth::user()->user_type == UserType::USER):
                if(Auth::user()->business):
                    $business_id = Auth::user()->business->id;
                elseif(Auth::user()->userBusiness):
                    $business_id = Auth::user()->userBusiness->id;
                endif;
                $user->business_id = $business_id;

                if(business() && $request->user_type == UserType::ADMIN): 
                    $user->permissions =  Auth::user()->permissions;
                else:
                    $userRole          = Role::find(3);
                    $user->permissions = $userRole->permissions;
                endif;
            elseif($request->user_type == UserType::SUPERADMIN && Auth::user()->user_type == UserType::SUPERADMIN):
                $user->permissions = Role::find(1)->permissions; 
            else:
                $user->business_id =  $request->business_id;
                $subscription      = Subscription::where('business_id',$request->business_id)->first();
                if($request->user_type == UserType::ADMIN):
                    $user->permissions =  businessPlanPermission($subscription->plan_id); 
                else:
                    $user->permissions =  Role::find(3)->permissions; 
                endif;
            endif;
            if($request->branch_id):
                $user->branch_id = $request->branch_id;
            endif;
            if($request->avatar):
            $user->avatar         = $this->upload->upload('user','',$request->avatar);
            endif;
            $user->status         =  $request->status == 'on' ? Status::ACTIVE:Status::INACTIVE;
            $user->email_verified =  1;
            $user->save();

            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
    public function edit($id){
        return User::find($id);
    }
    //user update
    public function update($request){
        try {  
          
            $user                 =  User::find($request->id);
            $user->name           =  $request->name;
            $user->email          =  $request->email;
            $user->user_type      = $request->user_type;
            if($user->business_owner!==1):
                if($request->user_type == UserType::SUPERADMIN):
                    $user->role_id        =  Role::find(1)->id;
                elseif($request->user_type == UserType::ADMIN):
                    $user->role_id        = Role::find(2)->id;
                else:
                    $user->role_id        = Role::find(3)->id;
                endif; 
            endif;
            $user->phone          =  $request->phone;
            $user->address        =  $request->address;
            $user->about          =  $request->about;

            if($request->designation_id):
                $user->designation_id = $request->designation_id;
            endif;
            if($request->department_id):
                $user->department_id = $request->department_id;
            endif;

            if($user->business_owner!==1):
              
                if(Auth::user()->user_type == UserType::ADMIN || Auth::user()->user_type == UserType::USER):
                    if(Auth::user()->business):
                        $business_id = Auth::user()->business->id;
                    elseif(Auth::user()->userBusiness):
                        $business_id = Auth::user()->userBusiness->id;
                    endif; 
                    $user->business_id = $business_id; 
                    if(business() && $request->user_type == UserType::ADMIN): 
                        $user->permissions =  Auth::user()->permissions;
                    else:
                        $userRole          = Role::find(3);
                        $user->permissions = $userRole->permissions;
                    endif;
                elseif($request->user_type == UserType::SUPERADMIN && Auth::user()->user_type == UserType::SUPERADMIN): 
                    $user->permissions = Role::find(1)->permissions; 
                else: 
                    $user->business_id =  $request->business_id;
                    $subscription      = Subscription::where('business_id',$request->business_id)->first();
                    if($request->user_type == UserType::ADMIN):
                        $user->permissions =  businessPlanPermission($subscription->plan_id); 
                    else:
                        $user->permissions =  Role::find(3)->permissions; 
                    endif;
                endif;
                if($request->branch_id):
                    $user->branch_id = $request->branch_id;
                endif;
            endif;

            if($request->password):
            $user->password       =  Hash::make($request->password);
            endif;
            if($request->avatar):
            $user->avatar         = $this->upload->upload('user',$user->avatar,$request->avatar);
            endif;
            $user->status         =  $request->status == 'on' ? Status::ACTIVE:Status::INACTIVE;
            $user->save();

            return true;
        } catch (\Throwable $th) { 
            return false;
        }
    }
    //delete user
    public function delete($id){
        try {
            $user    = User::find($id);
            if($user && $user->upload && File::exists($user->upload->original)):
                unlink(public_path($user->upload->original));
                Upload::destroy($user->upload->id);
            endif;
            return $user->delete();

        } catch (\Throwable $th) {
            return false;
        }

    }

    //update user permissions
    public function permissionsUpdate($request){
        try {

            $user                    = User::find($request->user_id);
                $user->permissions   = $request->permissions ? $request->permissions:[];
            $user->save();
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    //user status update
    public function statusUpdate($id){
        try {
            $user                 =  User::find($id);
            if($user->status      == Status::ACTIVE):
            $user->status         =  Status::INACTIVE;
            elseif($user->status  == Status::INACTIVE):
            $user->status         =  Status::ACTIVE;
            endif;
            $user->save();
            return true;
        } catch (\Throwable $th) {
           return false;
        }
    }

    //user can ban or unban
    public function BanUnban($id){
        try {
            $user                 =  User::find($id);
            if($user->is_ban      == BanUser::BAN):
            $user->is_ban         =  BanUser::UNBAN;
            elseif($user->is_ban  == BanUser::UNBAN):
            $user->is_ban         =  BanUser::BAN;
            endif;
            $user->save();

            return true;
        } catch (\Throwable $th) {
           return false;
        }
    }

    public function businessUsers($business_id){
        $business = Business::find($business_id);
       return  User::where(['status'=>Status::ACTIVE,'is_ban'=>BanUser::UNBAN])->where(function($query)use($business,$business_id){
            $query->where('business_id',$business_id);
            $query->orWhere('id',$business->owner_id);
        })->orderByDesc('id')->get();
    }

    public function getBusinessUsers($business_id){
        $business = Business::find($business_id);
        return  User::where(['status'=>Status::ACTIVE,'is_ban'=>BanUser::UNBAN])->where(function($query)use($business,$business_id){
            $query->where('business_id',$business_id);
            $query->orWhere('id',$business->owner_id);
        })->orderByDesc('id')->get();
    }


    public function checkUserCount(){
        try {
            $subscription = Subscription::where('business_id',business_id())->first();
            if($subscription && $subscription->plan):
                $user_count        = @$subscription->plan->user_count;
                $exists_users      = User::where('business_id',business_id())->count(); 
                if($exists_users < $user_count):
                    return true;
                else:
                    return false;
                endif;
            else:
            endif;
            return true;
        } catch (\Throwable $th) {
           return false;
        }
    }

    
}
