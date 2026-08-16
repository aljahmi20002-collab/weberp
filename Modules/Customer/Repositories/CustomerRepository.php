<?php
namespace Modules\Customer\Repositories;

use App\Enums\Status;
use App\Repositories\Upload\UploadInterface;
use Illuminate\Support\Facades\Auth;
use Modules\Customer\Entities\Customer;
use Modules\Sell\Entities\Sale;
use Modules\Sell\Entities\SalePayment;

class CustomerRepository implements CustomerInterface{
    protected $upload;
    public function __construct(UploadInterface $upload)
    {
        $this->upload = $upload;
    }
    public function get(){
        return Customer::where(function($query){
            if(business()):
                $query->where('business_id',business_id());
            elseif(isUser()):
                $query->where(['business_id'=>business_id()]);
            endif;
        })->orderByDesc('id')->paginate(10);
    }

    public function getAllCustomers(){
        return Customer::where(function($query){
            if(business()):
                $query->where('business_id',business_id());
            elseif(isUser()):
                $query->where(['business_id'=>business_id()]);
            endif;
        })->orderByDesc('id')->get();
    }

    public function getActiveCustomers($business_id){
        return Customer::where(function($query){
            if(business()):
                $query->where('business_id',business_id());
            elseif(isUser()):
                $query->where(['business_id'=>business_id()]);
            endif;
            $query->where('status',Status::ACTIVE);
        })->orderByDesc('id')->get();
    }

    public function getFind($id){
        return Customer::find($id);
    }

    public function store($request){

        try {
            $customer                 = new Customer();
            if(isSuperadmin()):
                $business_id = $request->business_id;
            else:
                $business_id = business_id();
            endif;
            $customer->business_id    = $business_id;
            $customer->name           = $request->name;
            $customer->phone          = $request->phone;
            $customer->email          = $request->email;
            if($request->image):
                $customer->image_id  = $this->upload->upload('customer','',$request->image);
            endif;
            $customer->address        = $request->address;
            if($request->opening_balance):
                $customer->opening_balance= $request->opening_balance;
                $customer->balance        = $request->opening_balance;
            endif;
            $customer->status         = $request->status == 'on'? Status::ACTIVE:Status::INACTIVE;
            $customer->save();
            return true;

        } catch (\Throwable $th) {
           return false;
        }
    }
    public function update($id,$request){
        try {
            $customer                 = Customer::find($id);
            if(isSuperadmin()):
                $business_id = $request->business_id;
            else:
                $business_id = business_id();
            endif;
            $customer->business_id    = $business_id;
            $customer->name           = $request->name;
            $customer->phone          = $request->phone;
            $customer->email          = $request->email;
            if($request->image):
                $customer->image_id  = $this->upload->upload('customer',$customer->image_id,$request->image);
            endif;
            $customer->address        = $request->address;
            if($request->opening_balance):
            $customer->opening_balance= $request->opening_balance;
            $customer->balance        = $request->opening_balance;
            endif;
            $customer->status         = $request->status == 'on'? Status::ACTIVE:Status::INACTIVE;
            $customer->save();
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
    public function delete($id){
        try {
            $customer = $this->getFind($id);
            $this->upload->unlinkImage($customer->image_id);
            return Customer::destroy($id);
        } catch (\Throwable $th) {
            return false;
        }
    }
    public function statusUpdate($id){
        try {
            $customer         = $this->getFind($id);
            $customer->status = $customer->status == Status::ACTIVE ? Status::INACTIVE:Status::ACTIVE;
            $customer->save();
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    public function totalSalesPayments ($customer_id){
        $sales = Sale::where('customer_id',$customer_id)->where(function($query)use($customer_id){
                    $query->where('business_id',business_id());
                    if(isUser()):
                        $query->where('branch_id',Auth::user()->branch_id);
                    endif;
                })->orderBy('id','desc')->get();
        $data                  = [];
        $data['total_amount']  = 0;
        $data['total_payment'] = 0;
        $data['total_due']     = 0;
        foreach ($sales as  $sale) {
            $data['total_amount']   += $sale->TotalSalePrice;
            $data['total_payment']  += $sale->payments->sum('amount');
            $data['total_due']      += $sale->DueAmount;
        }
        return $data;
    }

}
