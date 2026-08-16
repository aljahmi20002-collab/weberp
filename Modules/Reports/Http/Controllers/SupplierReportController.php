<?php

namespace Modules\Reports\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Branch\Repositories\BranchInterface;
use Modules\Reports\Repositories\SupplierReport\SupplierReportInterface;
use Modules\Supplier\Repositories\SupplierInterface;

class SupplierReportController extends Controller
{
    protected $branchRepo,$supplierRepo,$repo;
    public function __construct(BranchInterface $branchRepo,SupplierInterface $supplierRepo,SupplierReportInterface $repo)
    {
        $this->branchRepo   = $branchRepo;
        $this->supplierRepo = $supplierRepo;
        $this->repo         = $repo;
    }

    public function index(Request $request)
    { 
        $request['date']  =  Carbon::today()->format('m/d/Y').' - '.Carbon::today()->format('m/d/Y');
        $branches         =  $this->branchRepo->getAll(business_id()); 
        $suppliers        =  $this->supplierRepo->getActive();
        $supplier         =  null;
        return view('reports::supplier_report.index',compact('request','branches','suppliers','supplier'));
    }

    public function getReport(Request $request){  
     
        $data  = [];
        $supplier       = $this->supplierRepo->getFind($request->supplier_id);
        if($request->branch_id && !blank($request->branch_id)):
            $branch                   = $this->branchRepo->getFind($request->branch_id);
            $branchname               = @$branch->name;
        elseif(isUser()):
            $branchname           = Auth::user()->branch->name;
        else:
            $branchname           = __('all');
        endif;  
        if($request->report_type == 'purchase_return_report'):
            $purchases_return = $this->repo->getPurchaseReturnReport($request); 
            return view('reports::supplier_report.purchase_return_list',compact('request','data','branchname','supplier','purchases_return'))->render();
        else:
            $purchases = $this->repo->getPurchaseReport($request); 
            return view('reports::supplier_report.purchase_list',compact('request','data','branchname','supplier','purchases'))->render();
        endif;
    } 

}
