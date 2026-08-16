<?php
namespace App\Http\ViewComposer\BusinessDashboard;

use App\Repositories\BusinessDashboard\BusinessDashboardInterface;
use Illuminate\View\View;
class ThirtyDaysSaleChartComposer{
    protected $repo;
    public function __construct(BusinessDashboardInterface $repo)
    {   
        $this->repo = $repo;
    } 

    public function compose(View $view){
        $data  = $this->repo->ThirtyDaysSalesChart(); 
        $view->with('ThirtyDaysSalesData',$data);
    }
}