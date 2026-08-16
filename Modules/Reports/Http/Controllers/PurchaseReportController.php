<?php

namespace Modules\Reports\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Branch\Repositories\BranchInterface;
use Modules\Reports\Repositories\PurchaseReport\PurchaseReportInterface;

class PurchaseReportController extends Controller
{

    protected $branchRepo,$repo;
    public function __construct(BranchInterface $branchRepo,PurchaseReportInterface $repo)
    {
        $this->branchRepo   = $branchRepo;
        $this->repo         = $repo;
    }
    public function index(Request $request){
        $branches         = $this->branchRepo->getAll(business_id());
        return view('reports::purchase_report.index',compact('branches','request'));
    }

    public function getReport(Request $request){
        if($request->ajax()): 
           $purchases = $this->repo->getReport($request);
           if($request->branch_id && !blank($request->branch_id)):
                $branch                   = $this->branchRepo->getFind($request->branch_id);
                $branchname               = @$branch->name;
            elseif(isUser()):
                $branchname           = Auth::user()->branch->name;
            else:
                $branchname           = __('all');
            endif; 
           return view('reports::purchase_report.purchase_list',compact('purchases','request','branchname'));
        endif;
        return '';
    }
    
}
