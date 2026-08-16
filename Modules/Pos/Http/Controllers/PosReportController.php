<?php

namespace Modules\Pos\Http\Controllers;
 
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Branch\Repositories\BranchInterface;
use Modules\Pos\Repositories\PosReport\PosReportInterface;

class PosReportController extends Controller
{
    protected $branchRepo,$repo;
    public function __construct(BranchInterface $branchRepo,PosReportInterface $repo)
    {
        $this->branchRepo   = $branchRepo;
        $this->repo         = $repo;
    }
    public function index(Request $request){
        $branches         = $this->branchRepo->getAll(business_id());
        return view('pos::pos_report.index',compact('branches','request'));
    }

    public function getReport(Request $request){
        if($request->ajax()):   
           $poses = $this->repo->getReport($request);
           if($request->branch_id && !blank($request->branch_id)):
                $branch                   = $this->branchRepo->getFind($request->branch_id);
                $branchname               = @$branch->name;
            elseif(isUser()):
                $branchname           = Auth::user()->branch->name;
            else:
                $branchname           = __('all');
            endif; 
           return view('pos::pos_report.pos_list',compact('poses','request','branchname'));
        endif;
        return '';
    }
}
