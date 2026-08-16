<?php
namespace Modules\Pos\Repositories\PosReport;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Modules\Pos\Entities\Pos;

class PosReportRepository implements PosReportInterface {
    public function getReport($request){ 
        $date = explode(' - ', $request->date);
        if(is_array($date)) {
            $from   = Carbon::parse(trim($date[0]))->startOfDay()->toDateTimeString();
            $to     = Carbon::parse(trim($date[1]))->endOfDay()->toDateTimeString(); 
        }   
        
        $poses = Pos::where(function($query)use($request){
                            $query->where('business_id',business_id());
                            if(isUser()):
                                $query->where('branch_id',Auth::user()->branch_id);
                            else:
                                if($request->branch_id):
                                    $query->where('branch_id',$request->branch_id);
                                endif;
                            endif;
                        })->whereBetween('updated_at',[$from,$to])->orderByDesc('id')->paginate(10);
        return $poses;
    }
}