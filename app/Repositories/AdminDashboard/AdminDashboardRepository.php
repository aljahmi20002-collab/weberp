<?php
namespace App\Repositories\AdminDashboard;
use App\Repositories\AdminDashboard\AdminDashboardInterface;
use Carbon\Carbon;
use Modules\Business\Entities\Business;

class AdminDashboardRepository implements AdminDashboardInterface {
    public function businessRegisterChart(){
        $data =[];
        $start_date = Carbon::today()->addDay(1)->subDays(30)->startOfDay()->toDateTimeString(); 
        $data['dates']                 = []; 
        $data['business_payment']      = [];   
        $data['business_count']        = [];   
        for ($i=0; $i <30 ; $i++) { 
            $d                  = Carbon::parse($start_date)->addDay($i)->format('d-m-Y');  
            $data['dates'][]    = $d;
            $start_d            = Carbon::parse($start_date)->addDay($i)->startOfDay()->toDateTimeString();
            $end_d              = Carbon::parse($start_date)->addDay($i)->endOfDay()->toDateTimeString();
            $businesses         = Business::whereBetween('created_at',[$start_d,$end_d])->get();  
            $data['business_count'][$d]= $businesses->count();  
            $totalPayment       = 0;
            foreach ($businesses as $business) { 
                $totalPayment += $business->subscription->sum('plan_price');
            }
            $data['business_payment'][$d]         = $totalPayment;  
        } 
        return $data;
    }
}