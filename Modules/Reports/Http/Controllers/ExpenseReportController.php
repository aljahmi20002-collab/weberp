<?php

namespace Modules\Reports\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Branch\Repositories\BranchInterface; 
use Modules\Reports\Repositories\ExpenseReport\ExpenseReportInterface;

class ExpenseReportController extends Controller
{
    protected $branchRepo,$repo;
    public function __construct(BranchInterface $branchRepo,ExpenseReportInterface $repo)
    {
        $this->branchRepo     = $branchRepo;
        $this->repo           = $repo;
    }
    public function index(Request $request)
    { 
        $request['date']  =  Carbon::today()->format('m/d/Y').' - '.Carbon::today()->format('m/d/Y');
        $branches     =  $this->branchRepo->getAll(business_id()); 
        return view('reports::expense_report.index',compact('request','branches'));
    }

    public function getReport(Request $request){  
        $data  = [];
        if($request->branch_id && !blank($request->branch_id)):
            $branch                   = $this->branchRepo->getFind($request->branch_id);
            $branchname               = @$branch->name;
        elseif(isUser()):
            $branchname           = Auth::user()->branch->name;
        else:
            $branchname           = __('all');
        endif;  
        $expenses = $this->repo->getReport($request); 
        return view('reports::expense_report.expense_list',compact('request','data','branchname','expenses'))->render();
    }
}
