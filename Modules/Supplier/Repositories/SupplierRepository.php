<?php
namespace Modules\Supplier\Repositories;

use App\Enums\Status;
use Illuminate\Support\Facades\Auth;
use Modules\Supplier\Entities\Supplier;
use Modules\Supplier\Repositories\SupplierInterface;
class SupplierRepository implements SupplierInterface {
    public function get(){
        
        return Supplier::where('business_id',business_id())->orderByDesc('id')->paginate(10);
    }
    public function getAll(){ 
        return Supplier::where('business_id',business_id())->orderByDesc('id')->get();
    }
    public function getActive(){
        return Supplier::where('business_id',business_id())->where('status',Status::ACTIVE)->orderByDesc('id')->get();
    }

    public function getFind($id){
        return Supplier::find($id);
    }
    public function store($request){
      
        try {
            $supplier                 = new Supplier();
            if(isSuperadmin()):
                $business_id = $request->business_id; 
            else: 
                $business_id = business_id();  
            endif;
            $supplier->business_id    = $business_id; 
            $supplier->name           = $request->name;
            $supplier->company_name   = $request->company_name;
            $supplier->phone          = $request->phone;
            $supplier->email          = $request->email;
            $supplier->address        = $request->address;
            if($request->opening_balance):
            $supplier->opening_balance= $request->opening_balance;
            $supplier->balance        = $request->opening_balance;
            endif;
            $supplier->status         = $request->status == 'on'? Status::ACTIVE:Status::INACTIVE;
            $supplier->save();
            return true;

        } catch (\Throwable $th) { 
           return false;
        }
    }
    public function update($id,$request){
        try { 
            $supplier                 = Supplier::find($id);
            if(isSuperadmin()):
                $business_id = $request->business_id; 
            else: 
                $business_id = business_id(); 
            endif;
            $supplier->business_id    = $business_id; 
            $supplier->name           = $request->name;
            $supplier->company_name   = $request->company_name;
            $supplier->phone          = $request->phone;
            $supplier->email          = $request->email;
            $supplier->address        = $request->address;
            if($request->opening_balance):
            $supplier->opening_balance= $request->opening_balance;
            $supplier->balance        = $request->opening_balance;
            endif;
            $supplier->status         = $request->status == 'on'? Status::ACTIVE:Status::INACTIVE;
            $supplier->save();
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
    public function delete($id){
        try { 
            return Supplier::destroy($id);
        } catch (\Throwable $th) {
            return false;
        }
    }
    public function statusUpdate($id){
        try {
            $supplier         = $this->getFind($id);
            $supplier->status = $supplier->status == Status::ACTIVE ? Status::INACTIVE:Status::ACTIVE;
            $supplier->save();
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
}