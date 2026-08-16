<?php
namespace Modules\Business\Repositories;

use App\Enums\Status;
use App\Enums\UserType;
use App\Models\Backend\Role;
use App\Models\User;
use App\Repositories\Upload\UploadInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\Account\Repositories\AccountInterface;
use Modules\Branch\Entities\Branch;
use Modules\Branch\Repositories\BranchInterface;
use Modules\Business\Entities\Business;
use Modules\Plan\Entities\Plan;
use Modules\Plan\Enums\IsDefault;
use Modules\Subscription\Repositories\SubscriptionInterface;

class BusinessRepository implements BusinessInterface {

    protected $uploadRepo,$businessModel,$branchRepo,$subsRepo,$accountRepo;
    public function __construct(
        Business $businessModel,
        UploadInterface $uploadRepo,
        BranchInterface $branchRepo,
        SubscriptionInterface $subsRepo,
        AccountInterface $accountRepo

        )
    {
        $this->businessModel = $businessModel;
        $this->uploadRepo    = $uploadRepo;
        $this->branchRepo    = $branchRepo;
        $this->subsRepo      = $subsRepo;
        $this->accountRepo   = $accountRepo;
    }
    public function get(){
        return $this->businessModel::orderByDesc('id')->paginate(10);
    }
    public function getAllBusinesses(){
        return $this->businessModel::orderByDesc('id')->get();
    }
    public function getAll(){
        return $this->businessModel::where('status',Status::ACTIVE)->orderByDesc('id')->get();
    }
    public function getFind($id){
        return $this->businessModel::find($id);
    }
    public function store($request){
     
        try { 
           
            $business = new $this->businessModel();
            $business->business_name    = $request->business_name;
            $business->start_date       = $request->start_date;
            if($request->logo):
                $business->logo         = $this->uploadRepo->upload('business','',$request->logo);
            endif;
            $business->currency_id      = $request->currency;
            $business->owner_id         = $request->owner_id;
            if($request->sku_prefix):
            $business->sku_prefix       = $request->sku_prefix;
            endif;
            if($request->default_profit_percent):
                $business->default_profit_percent  = $request->default_profit_percent !=''? $request->default_profit_percent:0;
            endif;
            $business->save(); 
         
            //default account create
            if($business):
                $this->accountRepo->defaultAccountStore($business->id);
            endif;
            //end default account create
            if($request->signup):
                return $business->id;
            else:
                return true;
            endif;

        } catch (\Throwable $th) { 
            return false;
        }
    }
    public function update($id,$request){
        try {
            $business = $this->businessModel::find($id);
            $business->business_name    = $request->business_name;
            $business->start_date       = $request->start_date;
            if($request->logo):
                $business->logo             = $this->uploadRepo->upload('business',$business->logo,$request->logo);
            endif;
            $business->currency_id      = $request->currency;
            if($request->sku_prefix):
            $business->sku_prefix       = $request->sku_prefix;
            endif;
            if($request->default_profit_percent):
                $business->default_profit_percent  = $request->default_profit_percent !=''? $request->default_profit_percent:0;
            endif;
            $business->status           = $request->status  == 'on'? Status::ACTIVE:Status::INACTIVE;
            $business->save();
            return true;

        } catch (\Throwable $th) {
            return false;
        }
    }

    public function delete($id){
        try {
            $business= $this->businessModel::find($id);
            $this->uploadRepo->unlinkImage($business->logo);
            return User::destroy($business->owner_id);
        } catch (\Throwable $th) {
             return false;
        }
    }

    public function statusUpdate($id){
        try {
            $business         = Business::find($id);
            $branches         = Branch::where('business_id',$business->id)->get();
            $users            = User::where('business_id',$business->id)->orWhere('id',$business->owner_id)->get();
            foreach ($users as $user) {
              $user->status   = $business->status == Status::ACTIVE ? Status::INACTIVE:Status::ACTIVE;
              $user->save();
            }
            foreach ($branches as $branch) {
                $branchFind         = $this->branchRepo->getFind($branch->id);
                $branchFind->status = $business->status == Status::ACTIVE ? Status::INACTIVE:Status::ACTIVE;
                $branchFind->save();
            }
            $business->status = $business->status == Status::ACTIVE ? Status::INACTIVE:Status::ACTIVE;
            $business->save();
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }


    public function CreateBusiness($request){
        try {     
           
            $role = Role::find(2);
            $plan = Plan::where(['status'=>Status::ACTIVE,'is_default'=>IsDefault::YES])->first();
            
            $user = User::create([
                'name'           => $request->name,
                'email'          => $request->email,
                'phone'          => $request->business_phone,
                'user_type'      => UserType::ADMIN,
                'role_id'        => $role ? $role->id : null,
                'permissions'    => businessPlanPermission($plan->id),
                'password'       => Hash::make($request->password),
                'business_owner' => 1, 
                'email_verified' => 1,
                'email_verified_at'=>Date('Y-m-d H:i:s'),
                'status'         => $request->status == 'on' ? Status::ACTIVE:Status::INACTIVE
            ]);  
            if($user):
                $request['owner_id'] = $user->id;
                $request['signup']   = true;
                $business_id         = $this->store($request);//create business

                $business = $this->getFind($business_id);
                $business->status  = $request->status == 'on' ? Status::ACTIVE : Status::INACTIVE;
                $business->save();

                if($business_id):
                    $request['business_id'] = $business_id;
                    $request['branch_name'] = $request->business_name;
                    $this->branchRepo->store($request);//create branch

                    //subscription
                    $plan                 = Plan::where(['status'=>Status::ACTIVE,'is_default'=>IsDefault::YES])->first();
                    $request['plan_id']   = $plan->id;
                    $request['paid_via']  = $plan->name;
                    $this->subsRepo->subscription($request);
                 
                    return true;
                endif; 
                return false;
            else: 
                return false;
            endif;  
            return false;

        } catch (\Throwable $th) {  
            return false;
        }
    }
   

}
