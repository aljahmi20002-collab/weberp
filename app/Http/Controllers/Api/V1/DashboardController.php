<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\DashboardResource;
use App\Repositories\BusinessDashboard\BusinessDashboardInterface;
use App\Traits\ApiReturnFormatTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    use ApiReturnFormatTrait;
    protected $repo,$data=[];
    public function __construct(BusinessDashboardInterface $repo)
    {
        $this->repo = $repo;
    }
    public function summeryCount(Request $request){ 
       
        $this->data['total_product_count']         = @$this->repo->summeryCount($request)['total_product_count'];
        $this->data['total_sales_count']           = @$this->repo->summeryCount($request)['total_sales_count'];
        $this->data['total_service_sale_count']    = @$this->repo->summeryCount($request)['total_service_sale_count'];
        $this->data['total_pos_count']             = @$this->repo->summeryCount($request)['total_pos_count'];
        $this->data['total_purchase_count']        = @$this->repo->summeryCount($request)['total_purchase_count'];
        $this->data['total_purchase_return_count'] = @$this->repo->summeryCount($request)['total_purchase_return_count'];
        $summery = new DashboardResource((object)$this->data);
        return $this->responseWithSuccess(__('success'),[
            'summery' => $summery
        ],200);
     
    }


    public function salesCharts(){
        $sales   = $this->repo->salesChartsData(); 
        return $this->responseWithSuccess(__('success'),[
            'sales_charts'   => $sales
        ],200);
    }
    public function purchaseCharts(){
        $sales   = $this->repo->purchaseChartsData(); 
        return $this->responseWithSuccess(__('success'),[
            'sales_charts'   => $sales
        ],200);
    }
}
