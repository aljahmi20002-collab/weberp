<?php

namespace Modules\Pos\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Branch\Repositories\BranchInterface;
use Modules\Pos\Repositories\ProductWisePosProfit\ProductWisePosProfitInterface;

class ProductWisePosProfitController extends Controller
{
    protected $branchRepo,$repo;
    public function __construct(BranchInterface $branchRepo,ProductWisePosProfitInterface $repo)
    {
        $this->branchRepo     = $branchRepo;
        $this->repo           = $repo;
    }
    public function index(Request $request)
    {
        $request['date']  =  Carbon::today()->format('m/d/Y').' - '.Carbon::today()->format('m/d/Y');
        $branches     =  $this->branchRepo->getAll(business_id());
        $products     =  $this->repo->getProfit($request); 
        return view('pos::product_wise_pos_profit_report.index',compact('request','branches','products'));
    }

    public function getProfit(Request $request){
        $products  =  $this->repo->getProfit($request);
        $branches  = $this->branchRepo->getAll(business_id());
        $branchname    = $this->branchRepo->getFind($request->branch_id);   
        $request['filter'] = 'true';
        return view('pos::product_wise_pos_profit_report.index',compact('request','branches','products','branchname'));
    }
}
