<?php

namespace Modules\Reports\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Branch\Repositories\BranchInterface;
use Modules\Reports\Repositories\ProductWiseProfit\ProductWiseProfitInterface;

class ProductWiseProfitController extends Controller
{
    protected $branchRepo,$repo;
    public function __construct(BranchInterface $branchRepo,ProductWiseProfitInterface $repo)
    {
        $this->branchRepo     = $branchRepo;
        $this->repo           = $repo;
    }
    public function index(Request $request)
    {
        $request['date']  =  Carbon::today()->format('m/d/Y').' - '.Carbon::today()->format('m/d/Y');
        $branches     =  $this->branchRepo->getAll(business_id());
        $products     =  $this->repo->getProfit($request); 
        return view('reports::product_wise_profit.index',compact('request','branches','products'));
    }

    public function getProfit(Request $request){
        $products  =  $this->repo->getProfit($request);
        $branches  = $this->branchRepo->getAll(business_id());
        $branchname     = $this->branchRepo->getFind($request->branch_id);   
        $request['filter'] = 'true';
        return view('reports::product_wise_profit.index',compact('request','branches','products','branchname'));
    }
}
