<?php
namespace Modules\Branch\Repositories;

use App\Enums\Status;
use Modules\Branch\Repositories\BranchInterface;
use Modules\Branch\Entities\Branch;
class BranchRepository implements BranchInterface
{
    protected $branchModel;
    public function __construct(Branch $branchModel)
    {
        $this->branchModel = $branchModel;
    }
    public function get($business_id){
        return $this->branchModel::where('business_id',$business_id)->orderBy('id','desc')->paginate(10);
    }
    public function getAllBranch($business_id){
        return $this->branchModel::where('business_id',$business_id)->orderBy('id','desc')->get();
    }
    public function getBranch($business_id){
        return $this->branchModel::where('business_id',$business_id)->orderBy('id','desc')->get();
    }
    public function getAll($business_id){
        return $this->branchModel::where(['business_id'=>$business_id,'status'=>Status::ACTIVE])->orderBy('id','desc')->get();
    }
 
    public function getBranches($business_id){
        return $this->branchModel::where(['status'=>Status::ACTIVE])->where(function($query)use($business_id){
            if(isSuperadmin()):
                $query->where('business_id',$business_id);
            else:
                $query->where('business_id',business_id());
            endif;
        })->orderBy('id','desc')->get();
    }
 
    public function getFind($id){
        return $this->branchModel::find($id);
    }
    public function store($request){
        try { 
            $branch                 = new $this->branchModel();
            $branch->business_id    = $request->business_id;
            $branch->name           = $request->branch_name;
            $branch->email          = $request->email;
            $branch->country_id     = $request->country;
            $branch->website        = $request->website;
            $branch->phone          = $request->business_phone;
            $branch->state          = $request->state;
            $branch->city           = $request->city;
            $branch->zip_code       = $request->zip_code;
            $branch->status         = $request->status  == 'on'? Status::ACTIVE:Status::INACTIVE;
            $branch->save();
            return true;

        } catch (\Throwable $th) {
            return false;
        }
    }

    public function update($id,$request){
        try {
            $branch                 = $this->branchModel::find($id);
            $branch->business_id    = $request->business_id;
            $branch->name           = $request->branch_name;
            $branch->email          = $request->email;
            $branch->country_id     = $request->country;
            $branch->website        = $request->website;
            $branch->phone          = $request->business_phone;
            $branch->state          = $request->state;
            $branch->city           = $request->city;
            $branch->zip_code       = $request->zip_code;
            $branch->status         = $request->status  == 'on'? Status::ACTIVE:Status::INACTIVE;
            $branch->save();
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    } 
    public function delete($id){
        return $this->branchModel::destroy($id);
    }
    public function statusUpdate($id){
        try {
            $branch  = $this->branchModel::find($id);
            $branch->status = $branch->status == Status::ACTIVE ? Status::INACTIVE:Status::ACTIVE;
            $branch->save();
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
}
