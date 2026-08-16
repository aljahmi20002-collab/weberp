<?php

namespace Modules\Reports\Http\Controllers;

use Carbon\Carbon; 
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Branch\Repositories\BranchInterface;
use Modules\Brand\Repositories\BrandInterface;
use Modules\Category\Repositories\CategoryInterface;
use Modules\Product\Traits\BusinessWiseDataFetch;
use Modules\Reports\Repositories\StockReport\StockReportInterface;
use Modules\Unit\Repositories\UnitInterface;

class StockReportController extends Controller
{
    use BusinessWiseDataFetch;

    protected $branchRepo,$repo,$brandRepo,$unitRepo,$categoryRepo;
    public function __construct(
        BranchInterface $branchRepo,
        StockReportInterface $repo,
        BrandInterface $brandRepo,
        UnitInterface $unitRepo,
        CategoryInterface $categoryRepo)
    {
        $this->branchRepo     = $branchRepo;
        $this->repo           = $repo;
        $this->brandRepo      = $brandRepo;
        $this->unitRepo       = $unitRepo;      
        $this->categoryRepo   = $categoryRepo;
    }
    public function index(Request $request)
    {  
        $branches         =  $this->branchRepo->getAll(business_id()); 
        $brands           =  $this->brandRepo->getBrands(business_id()); 
        $units            =  $this->unitRepo->getUnits(business_id());
        $categories       =  $this->categoryRepo->getActiveCategory(business_id());
        return view('reports::stock_report.index',compact('request','branches','brands','units','categories'));
    }

    public function getReport(Request $request){ 
       
        $data   = [];
        if($request->branch_id && !blank($request->branch_id)):
            $branch                   = $this->branchRepo->getFind($request->branch_id);
            $data['branch']           = @$branch->name; 
        else:
            $data['branch']           = __('all');
        endif;   
        $ProductVariationLocations = $this->repo->getReport($request); 
        $data['total_calculation'] = $this->repo->getTotalCalculation($ProductVariationLocations);
        $data['view']              = view('reports::stock_report.stock_list',compact('request','data','ProductVariationLocations'))->render();
        return $data;
    }
    public function subcategory(Request $request){
        if($request->ajax()): 
            $subcategories   = $this->categoryRepo->subcategory($request);
            return $this->subcategoryOptions($subcategories);
        else:
            return '<option value="">'.__('select').' '. __('subcategory').'</option>';
        endif;
    }
 
}
