<?php

namespace Modules\Reports\Repositories\SaleReport;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Modules\Sell\Entities\Sale;

class SaleReportRepository implements SaleReportInterface
{
    public function getReport($request)
    {

        $date = explode(' - ', $request->date);
        if (is_array($date)) {
            $from   = Carbon::parse(trim($date[0]))->startOfDay()->toDateTimeString();
            $to     = Carbon::parse(trim($date[1]))->endOfDay()->toDateTimeString();
        }
      
        $sales = Sale::where(function ($query) use ($request) {
            $query->where('business_id', business_id());
            if (isUser()):
                $query->where('branch_id', Auth::user()->branch_id);
            else:
                if ($request->branch_id) :
                    $query->where('branch_id', $request->branch_id);
                endif;
            endif;
        })->whereBetween('updated_at', [$from, $to])->orderByDesc('id')->paginate(10);
        return $sales;
    }
}
