<?php
namespace App\Http\ViewComposer\BranchDashboard;

use App\Repositories\BranchDashboard\BranchDashboardInterface;
use Illuminate\View\View;
class ThirtyDaysSaleChartComposer{
    protected $repo;
    public function __construct(BranchDashboardInterface $repo)
    {   
        $this->repo = $repo;
    } 

    public function compose(View $view){
        $data  = $this->repo->ThirtyDaysSalesChart(); 
        $view->with('ThirtyDaysSalesData',$data);
    }
}