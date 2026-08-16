<?php
namespace Modules\Reports\Repositories\ExpenseReport;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Modules\Expense\Entities\Expense; 

class ExpenseReportRepository implements ExpenseReportInterface{
    public function getReport($request){
      
        $date = explode(' - ', $request->date);
        if(is_array($date)) {
            $from   = Carbon::parse(trim($date[0]))->startOfDay()->toDateTimeString();
            $to     = Carbon::parse(trim($date[1]))->endOfDay()->toDateTimeString(); 
        }   

        return Expense::where(function($query)use($request){
            $query->where('business_id',business_id());
            if(isUser()):
                $query->where('branch_id',Auth::user()->branch_id);
            elseif($request->branch_id):
                $query->where('branch_id',$request->branch_id);
            endif;
        })->whereBetween('updated_at',[$from,$to])->paginate(10);
    }
}