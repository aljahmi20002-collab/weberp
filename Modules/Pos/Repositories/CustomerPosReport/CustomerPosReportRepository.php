<?php
namespace Modules\Pos\Repositories\CustomerPosReport;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Modules\Branch\Entities\Branch; 
use Modules\Customer\Enums\CustomerType;
use Modules\Customer\Repositories\CustomerInterface;
use Modules\Pos\Entities\Pos; 
class CustomerPosReportRepository implements CustomerPosReportInterface{
    protected $customerRepo;
    public function __construct(CustomerInterface $customerRepo)
    {
        $this->customerRepo    = $customerRepo;
    }
    public function getReport($request){ 
        
        $date = explode(' - ', $request->date);
        if(is_array($date)) {
            $from   = Carbon::parse(trim($date[0]))->startOfDay()->toDateTimeString();
            $to     = Carbon::parse(trim($date[1]))->endOfDay()->toDateTimeString(); 
        }   

       $sales  = Pos::where(function($query)use($request){
            $query->where('business_id',business_id());
            if(isUser()):
                $query->where('branch_id',Auth::user()->branch_id);
            else:
                if($request->branch_id):
                    $query->where('branch_id',$request->branch_id);
                endif;
            endif;
            if($request->customer_id):
                if($request->customer_id == 'walk_customer'):
                    $query->where('customer_type',CustomerType::WALK_CUSTOMER);
                else:
                    $query->where('customer_id',$request->customer_id);
                endif;
            endif;
        })->whereBetween('updated_at',[$from,$to])->get(); 

       $data=[];
       $data['total_sales_count']  = $sales->count();
       $data['total_sale_price']   = 0;
       $data['total_sale_payment'] = 0; 
       $data['total_sale_due']     = 0; 

       foreach ($sales as  $sale) {
            $data['total_sale_price']   += $sale->TotalSalePrice;
            $data['total_sale_payment'] += $sale->payments->sum('amount'); 
            $data['total_sale_due']     += $sale->DueAmount; 
       }
       $data['total_sale_price']   = number_format($data['total_sale_price'],2);
       $data['total_sale_payment'] = number_format($data['total_sale_payment'],2); 
       $data['total_sale_due']     = number_format($data['total_sale_due'],2); 
       
        return $data;
    }

    public function getSales($request){

        $date = explode(' - ', $request->date);
        if(is_array($date)) {
            $from   = Carbon::parse(trim($date[0]))->startOfDay()->toDateTimeString();
            $to     = Carbon::parse(trim($date[1]))->endOfDay()->toDateTimeString(); 
        }   
        return Pos::where(function($query)use($request){
            $query->where('business_id',business_id());
            if(isUser()):
                $query->where('branch_id',Auth::user()->branch_id);
            else:
                if($request->branch_id):
                    $query->where('branch_id',$request->branch_id);
                endif;
            endif;
            if($request->customer_id):
                if($request->customer_id == 'walk_customer'):
                    $query->where('customer_type',CustomerType::WALK_CUSTOMER);
                else:
                    $query->where('customer_id',$request->customer_id);
                endif;
            endif;
        })->whereBetween('updated_at',[$from,$to])->paginate(10);
      
    }

    public function getCustomerName($request){
        $customer = 'All';
        if($request->customer_id == 'walk_customer'):
            $customer = 'Walk Customer';
        elseif(!blank($request->customer_id) && $request->customer_id != 'walk_customer'):
            $customerFind = $this->customerRepo->getFind($request->customer_id);
            $customer = $customerFind->name;
        endif;
        return $customer;
    }
    public function getBranchName($request){
        $branchname   = 'All';
        if(isUser()):
            $branchname = Auth::user()->branch->name;
        elseif($request->branch_id):
            $branch      = Branch::find($request->branch_id);
            $branchname  = $branch->name;
        endif;
        return $branchname;
    }
}