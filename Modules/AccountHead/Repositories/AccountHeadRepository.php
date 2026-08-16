<?php
namespace Modules\AccountHead\Repositories;

use App\Enums\StatementType;
use App\Enums\Status;
use Modules\AccountHead\Entities\AccountHead;
use Modules\AccountHead\Repositories\AccountHeadInterface;
class AccountHeadRepository implements AccountHeadInterface{
    public function get(){
        return AccountHead::where(function($query){
            $query->where('business_id',business_id());
            $query->orWhere('is_default',Status::ACTIVE);
        })->orderByDesc('id')->paginate(10);
    }

    public function getAccountHead(){
        return AccountHead::where(function($query){
            $query->where('business_id',business_id());
            $query->orWhere('is_default',Status::ACTIVE);
        })->orderByDesc('id')->get();
    }

    public function getIncomeActiveHead(){
        return AccountHead::where(function($query){
            $query->where('business_id',business_id());
            $query->orWhere('is_default',Status::ACTIVE);
        })->where(['type'=>StatementType::INCOME,'status'=>Status::ACTIVE])->orderByDesc('id')->get();
    }
    public function getExpenseActiveHead(){
        return AccountHead::where(function($query){
            $query->where('business_id',business_id());
            $query->orWhere('status',Status::ACTIVE);
        })->where(['type'=>StatementType::EXPENSE,'status'=>Status::ACTIVE])->orderByDesc('id')->get();
    }
    
    public function getFind($id){
        return AccountHead::find($id);
    }
    public function store($request){
        try {
            $accountHead                = new AccountHead();
            $accountHead->business_id   = business_id();
            $accountHead->type          = $request->type;
            $accountHead->name          = $request->name;
            $accountHead->note          = $request->note;
            $accountHead->status        = $request->status == 'on'? Status::ACTIVE:Status::INACTIVE;
            $accountHead->save(); 
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
    public function update($id,$request){
        try {
            $accountHead                = $this->getFind($id);
            $accountHead->business_id   = business_id();
            $accountHead->type          = $request->type;
            $accountHead->name          = $request->name;
            $accountHead->note          = $request->note;
            $accountHead->status        = $request->status == 'on'? Status::ACTIVE:Status::INACTIVE;
            $accountHead->save(); 
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
    public function delete($id){
        return AccountHead::destroy($id);
    }
    public function statusUpdate($id){
        try {
            $accountHead         = $this->getFind($id);
            $accountHead->status = $accountHead->status == Status::ACTIVE? Status::INACTIVE:Status::ACTIVE;
            $accountHead->save();
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
}